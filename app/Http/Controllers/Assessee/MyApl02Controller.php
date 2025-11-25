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
     * Display a listing of user's APL-02 units.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $units = new LengthAwarePaginator([], 0, 10);
            $stats = ['total' => 0, 'not_started' => 0, 'in_progress' => 0, 'submitted' => 0, 'competent' => 0];
            return view('assessee.my-apl02.index', compact('units', 'stats'));
        }

        $query = Apl02Unit::where('assessee_id', $assesseeId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $units = $query->with(['scheme', 'schemeUnit', 'evidence'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Apl02Unit::where('assessee_id', $assesseeId)->count(),
            'not_started' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'not_started')->count(),
            'in_progress' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'in_progress')->count(),
            'submitted' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'submitted')->count(),
            'competent' => Apl02Unit::where('assessee_id', $assesseeId)->where('status', 'competent')->count(),
        ];

        return view('assessee.my-apl02.index', compact('units', 'stats'));
    }

    /**
     * Display the specified APL-02 unit with evidence.
     */
    public function show(Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $unit->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        $unit->load(['scheme', 'schemeUnit', 'evidence', 'reviews']);

        return view('assessee.my-apl02.show', compact('unit'));
    }

    /**
     * Show form to upload evidence for a unit.
     */
    public function uploadEvidence(Apl02Unit $unit)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $unit->assessee_id !== $assessee->id) {
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

        if (!$assessee || $unit->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        $validated = $request->validate([
            'evidence_type' => 'required|in:document,certificate,work_sample,project,photo,video,portfolio',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        // Store file
        $file = $request->file('file');
        $path = $file->store('apl02-evidence/' . $assessee->id, 'public');

        // Create evidence record
        $evidence = Apl02Evidence::create([
            'apl02_unit_id' => $unit->id,
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

        // Update unit status if needed
        if ($unit->status === 'not_started') {
            $unit->update(['status' => 'in_progress']);
        }

        // Update evidence count
        $unit->update([
            'total_evidence' => $unit->evidence()->count(),
        ]);

        return redirect()->route('admin.my-apl02.show', $unit)
            ->with('success', 'Bukti berhasil diupload.');
    }

    /**
     * Delete an evidence.
     */
    public function deleteEvidence(Apl02Unit $unit, Apl02Evidence $evidence)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $unit->assessee_id !== $assessee->id) {
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

        if (!$assessee || $unit->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        if ($unit->evidence()->count() === 0) {
            return redirect()->route('admin.my-apl02.show', $unit)
                ->with('error', 'Harap upload minimal 1 bukti sebelum submit.');
        }

        $unit->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('admin.my-apl02.show', $unit)
            ->with('success', 'Unit berhasil disubmit untuk review asesor.');
    }
}
