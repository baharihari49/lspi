<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Apl02Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyApl02FormController extends Controller
{
    /**
     * Get the current user's assessee record
     */
    private function getAssessee()
    {
        $user = auth()->user();
        return $user->assessee ?? null;
    }

    /**
     * Display a listing of user's APL-02 forms.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $forms = collect();
            $stats = ['total' => 0, 'draft' => 0, 'submitted' => 0, 'approved' => 0];
            return view('assessee.my-apl02-forms.index', compact('forms', 'stats'));
        }

        $query = Apl02Form::where('assessee_id', $assesseeId)
            ->with(['scheme', 'event', 'apl01Form', 'assessor']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $forms = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $stats = [
            'total' => Apl02Form::where('assessee_id', $assesseeId)->count(),
            'draft' => Apl02Form::where('assessee_id', $assesseeId)->where('status', 'draft')->count(),
            'submitted' => Apl02Form::where('assessee_id', $assesseeId)->whereIn('status', ['submitted', 'under_review'])->count(),
            'approved' => Apl02Form::where('assessee_id', $assesseeId)->where('status', 'approved')->count(),
        ];

        return view('assessee.my-apl02-forms.index', compact('forms', 'stats'));
    }

    /**
     * Display the specified APL-02 form.
     */
    public function show(Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        $apl02Form->load(['scheme.units', 'event', 'apl01Form', 'assessor']);

        return view('assessee.my-apl02-forms.show', compact('apl02Form'));
    }

    /**
     * Show form to edit APL-02.
     */
    public function edit(Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!$apl02Form->is_editable) {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form tidak dapat diubah pada status ini.');
        }

        $apl02Form->load(['scheme.units', 'event', 'apl01Form']);

        return view('assessee.my-apl02-forms.edit', compact('apl02Form'));
    }

    /**
     * Update the APL-02 form.
     */
    public function update(Request $request, Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!$apl02Form->is_editable) {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form tidak dapat diubah pada status ini.');
        }

        $validated = $request->validate([
            'self_assessment' => 'nullable|array',
            'self_assessment.*.unit_id' => 'required|integer',
            'self_assessment.*.is_competent' => 'nullable|boolean',
            'self_assessment.*.evidence_description' => 'nullable|string',
            'self_assessment.*.notes' => 'nullable|string',
            'portfolio_summary' => 'nullable|string',
            'work_experience' => 'nullable|array',
            'training_education' => 'nullable|array',
            'declaration_agreed' => 'nullable|boolean',
        ]);

        // Update self-assessment
        if (isset($validated['self_assessment'])) {
            $currentAssessment = $apl02Form->self_assessment ?? [];
            foreach ($validated['self_assessment'] as $unitData) {
                foreach ($currentAssessment as &$assessment) {
                    if ($assessment['unit_id'] == $unitData['unit_id']) {
                        $assessment['is_competent'] = $unitData['is_competent'] ?? null;
                        $assessment['evidence_description'] = $unitData['evidence_description'] ?? '';
                        $assessment['notes'] = $unitData['notes'] ?? '';
                        break;
                    }
                }
            }
            $validated['self_assessment'] = $currentAssessment;
        }

        // Handle declaration
        if ($request->has('declaration_agreed') && $request->declaration_agreed) {
            $validated['declaration_signed_at'] = now();
        }

        $apl02Form->update($validated);
        $apl02Form->updateCompletion();

        return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 berhasil disimpan.');
    }

    /**
     * Upload evidence files.
     */
    public function uploadEvidence(Request $request, Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!$apl02Form->is_editable) {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form tidak dapat diubah pada status ini.');
        }

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // Max 10MB per file
        ]);

        $evidenceFiles = $apl02Form->evidence_files ?? [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('apl02-forms/' . $assessee->id, 'public');

            $evidenceFiles[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_at' => now()->toIso8601String(),
            ];
        }

        $apl02Form->update(['evidence_files' => $evidenceFiles]);
        $apl02Form->updateCompletion();

        return redirect()->route('admin.my-apl02-forms.edit', $apl02Form)
            ->with('success', count($request->file('files')) . ' file berhasil diupload.');
    }

    /**
     * Delete an evidence file.
     */
    public function deleteEvidence(Request $request, Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!$apl02Form->is_editable) {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form tidak dapat diubah pada status ini.');
        }

        $request->validate([
            'file_index' => 'required|integer|min:0',
        ]);

        $evidenceFiles = $apl02Form->evidence_files ?? [];
        $fileIndex = $request->file_index;

        if (isset($evidenceFiles[$fileIndex])) {
            // Delete physical file
            if (isset($evidenceFiles[$fileIndex]['path'])) {
                Storage::disk('public')->delete($evidenceFiles[$fileIndex]['path']);
            }

            // Remove from array
            array_splice($evidenceFiles, $fileIndex, 1);
            $apl02Form->update(['evidence_files' => $evidenceFiles]);
        }

        return redirect()->route('admin.my-apl02-forms.edit', $apl02Form)
            ->with('success', 'File berhasil dihapus.');
    }

    /**
     * Submit form for review.
     */
    public function submit(Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!$apl02Form->is_submittable) {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form belum lengkap. Pastikan semua field wajib sudah diisi dan deklarasi sudah disetujui.');
        }

        $apl02Form->submit(auth()->id());

        return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 berhasil disubmit untuk review.');
    }

    /**
     * Resubmit form after revision.
     */
    public function resubmit(Apl02Form $apl02Form)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl02Form->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if ($apl02Form->status !== 'revision_required') {
            return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
                ->with('error', 'Form tidak dalam status revisi.');
        }

        $apl02Form->resubmit();

        return redirect()->route('admin.my-apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 berhasil disubmit ulang untuk review.');
    }
}
