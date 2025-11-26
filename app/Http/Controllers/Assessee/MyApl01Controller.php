<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Apl01Form;
use App\Models\Assessee;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $apl01->load(['scheme', 'event', 'answers', 'reviews']);

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

        $apl01->load(['scheme', 'event', 'answers']);

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

        $apl01->update($validated);

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
}
