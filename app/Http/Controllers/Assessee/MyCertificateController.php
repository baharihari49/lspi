<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MyCertificateController extends Controller
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
     * Display a listing of user's certificates.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $certificates = new LengthAwarePaginator([], 0, 10);
            $stats = ['total' => 0, 'active' => 0, 'expired' => 0, 'expiring_soon' => 0];
            return view('assessee.my-certificates.index', compact('certificates', 'stats'));
        }

        $query = Certificate::where('assessee_id', $assesseeId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('scheme_name', 'like', "%{$search}%");
            });
        }

        $certificates = $query->with(['scheme', 'assessmentResult'])
            ->orderBy('issue_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Certificate::where('assessee_id', $assesseeId)->count(),
            'active' => Certificate::where('assessee_id', $assesseeId)->where('status', 'active')->count(),
            'expired' => Certificate::where('assessee_id', $assesseeId)->where('status', 'expired')->count(),
            'expiring_soon' => Certificate::where('assessee_id', $assesseeId)
                ->where('status', 'active')
                ->where('valid_until', '<=', now()->addMonths(3))
                ->count(),
        ];

        return view('assessee.my-certificates.index', compact('certificates', 'stats'));
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        $assessee = $this->getAssessee();

        // Ensure user can only view their own certificates
        if (!$assessee || $certificate->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }

        $certificate->load(['scheme', 'assessmentResult', 'assessee']);

        return view('assessee.my-certificates.show', compact('certificate'));
    }

    /**
     * Download certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        $assessee = $this->getAssessee();

        // Ensure user can only download their own certificates
        if (!$assessee || $certificate->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }

        if (!$certificate->pdf_path || !file_exists(storage_path('app/' . $certificate->pdf_path))) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->download(
            storage_path('app/' . $certificate->pdf_path),
            $certificate->certificate_number . '.pdf'
        );
    }
}
