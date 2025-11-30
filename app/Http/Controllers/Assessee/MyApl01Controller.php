<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Apl01Answer;
use App\Models\Apl01Form;
use App\Models\Apl01FormField;
use App\Models\Assessee;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyApl01Controller extends Controller
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
     * Display a listing of user's APL-01 forms.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $forms = new LengthAwarePaginator([], 0, 10);
            $stats = ['total' => 0, 'draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0];
            return view('assessee.my-apl01.index', compact('forms', 'stats'));
        }

        $query = Apl01Form::where('assessee_id', $assesseeId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('form_number', 'like', "%{$search}%");
            });
        }

        $forms = $query->with(['scheme', 'event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Apl01Form::where('assessee_id', $assesseeId)->count(),
            'draft' => Apl01Form::where('assessee_id', $assesseeId)->where('status', 'draft')->count(),
            'submitted' => Apl01Form::where('assessee_id', $assesseeId)->where('status', 'submitted')->count(),
            'approved' => Apl01Form::where('assessee_id', $assesseeId)->where('status', 'approved')->count(),
            'rejected' => Apl01Form::where('assessee_id', $assesseeId)->where('status', 'rejected')->count(),
        ];

        return view('assessee.my-apl01.index', compact('forms', 'stats'));
    }

    /**
     * Display the specified APL-01 form.
     */
    public function show(Apl01Form $apl01)
    {
        $assessee = $this->getAssessee();

        // Ensure user can only view their own forms
        if (!$assessee || $apl01->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        $apl01->load(['scheme', 'event', 'tuk', 'eventSession', 'answers', 'reviews']);

        return view('assessee.my-apl01.show', compact('apl01'));
    }

    /**
     * Show the form for editing APL-01 (only if draft or needs revision).
     */
    public function edit(Apl01Form $apl01)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl01->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!in_array($apl01->status, ['draft', 'revised'])) {
            return redirect()->route('admin.my-apl01.show', $apl01)
                ->with('error', 'Form tidak dapat diedit karena sudah disubmit.');
        }

        $apl01->load([
            'scheme.formFields' => function ($query) {
                $query->active()
                    ->visible()
                    ->orderBy('section')
                    ->orderBy('order');
            },
            'event',
            'answers.formField',
        ]);

        return view('assessee.my-apl01.edit', compact('apl01'));
    }

    /**
     * Update the APL-01 form.
     */
    public function update(Request $request, Apl01Form $apl01)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl01->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!in_array($apl01->status, ['draft', 'revised'])) {
            return redirect()->route('admin.my-apl01.show', $apl01)
                ->with('error', 'Form tidak dapat diedit karena sudah disubmit.');
        }

        // Validate form data
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'current_industry' => 'nullable|string|max:255',
            'certification_purpose' => 'nullable|string',
            'target_competency' => 'nullable|string',
            'declaration_agreed' => 'nullable|boolean',
        ]);

        // Handle declaration
        $validated['declaration_agreed'] = $request->has('declaration_agreed');
        if ($validated['declaration_agreed'] && !$apl01->declaration_signed_at) {
            $validated['declaration_signed_at'] = now();
        }

        DB::beginTransaction();
        try {
            $apl01->update($validated);

            // Save dynamic field answers
            $this->saveFieldAnswers($apl01, $request);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.my-apl01.edit', $apl01)
                ->withInput()
                ->with('error', 'Gagal menyimpan formulir: ' . $e->getMessage());
        }

        // Check if submit action
        if ($request->input('action') === 'submit') {
            if (!$validated['declaration_agreed']) {
                return redirect()->route('admin.my-apl01.edit', $apl01)
                    ->with('error', 'Anda harus menyetujui pernyataan untuk submit formulir.');
            }

            $apl01->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            return redirect()->route('admin.my-apl01.show', $apl01)
                ->with('success', 'Form APL-01 berhasil disubmit untuk review.');
        }

        return redirect()->route('admin.my-apl01.show', $apl01)
            ->with('success', 'Form APL-01 berhasil diperbarui.');
    }

    /**
     * Submit the APL-01 form for review.
     */
    public function submit(Apl01Form $apl01)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $apl01->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke form ini.');
        }

        if (!in_array($apl01->status, ['draft', 'revised'])) {
            return redirect()->route('admin.my-apl01.show', $apl01)
                ->with('error', 'Form sudah disubmit sebelumnya.');
        }

        $apl01->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('admin.my-apl01.show', $apl01)
            ->with('success', 'Form APL-01 berhasil disubmit untuk review.');
    }

    /**
     * Save dynamic field answers
     */
    protected function saveFieldAnswers(Apl01Form $form, Request $request): void
    {
        $fields = Apl01FormField::where('scheme_id', $form->scheme_id)
            ->active()
            ->visible()
            ->get();

        foreach ($fields as $field) {
            $fieldKey = 'dynamic_field_' . $field->id;
            $value = $request->input($fieldKey);

            // Handle file uploads
            if ($field->field_type === 'file' && $request->hasFile($fieldKey)) {
                $file = $request->file($fieldKey);
                $path = $file->store('apl01-answers/' . $form->id, 'public');
                $value = $path;
            }

            // Skip if no value and not required
            if ($value === null && !$field->is_required) {
                continue;
            }

            // Handle array values (checkboxes, multi-select)
            $answerJson = null;
            if (is_array($value)) {
                $answerJson = $value;
                $value = implode(', ', $value);
            }

            // Update or create answer
            Apl01Answer::updateOrCreate(
                [
                    'apl01_form_id' => $form->id,
                    'form_field_id' => $field->id,
                ],
                [
                    'answer_value' => $value,
                    'answer_json' => $answerJson,
                    'is_valid' => true,
                    'review_status' => 'pending',
                ]
            );
        }
    }
}
