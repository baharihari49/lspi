<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Apl02Unit;
use App\Models\Apl02Evidence;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class MyApl02Controller extends Controller
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
     * Display a listing of user's APL-02 units grouped by event.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $eventGroups = collect();
            $stats = ['total' => 0, 'not_started' => 0, 'in_progress' => 0, 'submitted' => 0, 'competent' => 0];
            return view('assessee.my-apl02.index', compact('eventGroups', 'stats'));
        }

        $query = Apl02Unit::where('assessee_id', $assesseeId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get all units with event relationship
        $units = $query->with(['scheme', 'schemeUnit', 'evidence', 'event.scheme'])
            ->orderBy('event_id')
            ->orderBy('unit_code')
            ->get();

        // Group by event
        $eventGroups = $units->groupBy('event_id')->map(function ($eventUnits) {
            $event = $eventUnits->first()->event;
            return [
                'event' => $event,
                'units' => $eventUnits,
                'stats' => [
                    'total' => $eventUnits->count(),
                    'not_started' => $eventUnits->where('status', 'not_started')->count(),
                    'in_progress' => $eventUnits->where('status', 'in_progress')->count(),
                    'submitted' => $eventUnits->whereIn('status', ['submitted', 'under_review'])->count(),
                    'competent' => $eventUnits->where('status', 'competent')->count(),
                ],
            ];
        });

        // Global Statistics
        $stats = [
            'total' => Apl02Unit::where('assessee_id', $assesseeId)->count(),
            'not_started' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'not_started')->count(),
            'in_progress' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'in_progress')->count(),
            'submitted' => Apl02Unit::where('assessee_id', $assesseeId)->whereIn('status', ['submitted', 'under_review'])->count(),
            'competent' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'competent')->count(),
        ];

        return view('assessee.my-apl02.index', compact('eventGroups', 'stats'));
    }

    /**
     * Display the specified APL-02 unit with evidence.
     */
    public function show(Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || (int)$unit->assessee_id !== (int)$assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        $unit->load(['scheme', 'schemeUnit.elements', 'evidence', 'assessorReviews']);

        return view('assessee.my-apl02.show', compact('unit'));
    }

    /**
     * Show form to upload evidence for a unit.
     */
    public function uploadEvidence(Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || (int)$unit->assessee_id !== (int)$assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        if (!in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification'])) {
            return redirect()->route('admin.my-apl02.show', $unit)
                ->with('error', 'Unit sudah disubmit dan tidak dapat diubah.');
        }

        $unit->load(['scheme', 'schemeUnit']);

        return view('assessee.my-apl02.upload-evidence', compact('unit'));
    }

    /**
     * Store uploaded evidence.
     */
    public function storeEvidence(Request $request, Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || (int)$unit->assessee_id !== (int)$assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        // Validate common fields
        $validated = $request->validate([
            'evidence_type' => 'required|in:document,certificate,work_sample,project,photo,video,portfolio',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Check upload mode and validate accordingly
        $uploadMode = $request->input('upload_mode', 'file');
        $uploadedCount = 0;

        if ($uploadMode === 'file' && $request->hasFile('file')) {
            // Single file upload
            $request->validate([
                'file' => 'required|file|max:10240', // Max 10MB
            ]);

            $file = $request->file('file');
            $this->storeEvidenceFile($unit, $assessee, $file, $validated);
            $uploadedCount = 1;

        } elseif (($uploadMode === 'multiple' || $uploadMode === 'folder') && $request->hasFile('files')) {
            // Multiple files or folder upload
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'file|max:10240', // Max 10MB per file
            ]);

            $files = $request->file('files');
            foreach ($files as $index => $file) {
                // Skip directories (sometimes sent as empty files)
                if ($file->getSize() === 0 && !$file->getClientOriginalExtension()) {
                    continue;
                }

                // For multiple files, append index to title
                $fileTitle = count($files) > 1
                    ? $validated['title'] . ' (' . ($index + 1) . ')'
                    : $validated['title'];

                $fileValidated = array_merge($validated, ['title' => $fileTitle]);
                $this->storeEvidenceFile($unit, $assessee, $file, $fileValidated);
                $uploadedCount++;
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Silakan pilih file untuk diupload.');
        }

        // Update unit status if needed
        if ($unit->status === 'not_started') {
            $unit->update(['status' => 'in_progress']);
        }

        // Update evidence count
        $unit->update([
            'total_evidence' => $unit->evidence()->count(),
        ]);

        $message = $uploadedCount === 1
            ? 'Bukti berhasil diupload.'
            : "{$uploadedCount} bukti berhasil diupload.";

        return redirect()->route('admin.my-apl02.show', $unit)
            ->with('success', $message);
    }

    /**
     * Store a single evidence file.
     */
    private function storeEvidenceFile(Apl02Unit $unit, $assessee, $file, array $validated): Apl02Evidence
    {
        $path = $file->store('apl02-evidence/' . $assessee->id, 'public');

        return Apl02Evidence::create([
            'apl02_unit_id' => $unit->id,
            'assessee_id' => $assessee->id,
            'evidence_number' => 'EVD-' . date('Y') . '-' . str_pad(Apl02Evidence::count() + 1, 4, '0', STR_PAD_LEFT),
            'evidence_type' => $validated['evidence_type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'verification_status' => 'pending',
        ]);
    }

    /**
     * Delete an evidence.
     */
    public function deleteEvidence(Apl02Unit $unit, Apl02Evidence $evidence)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || (int)$unit->assessee_id !== (int)$assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        if ($evidence->apl02_unit_id !== $unit->id) {
            abort(403, 'Evidence tidak valid.');
        }

        if (!in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification'])) {
            return redirect()->route('admin.my-apl02.show', $unit)
                ->with('error', 'Unit sudah disubmit dan tidak dapat diubah.');
        }

        // Delete file
        if ($evidence->file_path) {
            Storage::disk('public')->delete($evidence->file_path);
        }

        $evidence->delete();

        // Update evidence count
        $unit->update([
            'total_evidence' => $unit->evidence()->count(),
        ]);

        return redirect()->route('admin.my-apl02.show', $unit)
            ->with('success', 'Bukti berhasil dihapus.');
    }

    /**
     * Submit unit for review.
     */
    public function submit(Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || (int)$unit->assessee_id !== (int)$assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        if ($unit->evidence()->count() === 0) {
            return redirect()->route('admin.my-apl02.show', $unit)
                ->with('error', 'Harap upload minimal 1 bukti sebelum submit.');
        }

        // Use model's submit method which auto-assigns assessor and creates review
        $unit->submit(auth()->id());

        // Reload to get fresh assessor relationship
        $unit->load('assessor');

        // Prepare success message based on what happened
        $message = 'Unit berhasil disubmit untuk review.';
        if ($unit->assessor_id) {
            $assessorName = $unit->assessor?->name ?? 'Assessor';
            $message = "Unit berhasil disubmit dan di-assign ke {$assessorName} untuk review.";
        }

        return redirect()->route('admin.my-apl02.show', $unit)
            ->with('success', $message);
    }
}
