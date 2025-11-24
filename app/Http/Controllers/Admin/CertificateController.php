<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\AssessmentResult;
use App\Models\CertificateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['assessee', 'scheme', 'issuedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter by assessee
        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        // Filter expiring soon
        if ($request->filled('expiring_soon')) {
            $days = $request->expiring_soon;
            $query->where('valid_until', '>=', now())
                  ->where('valid_until', '<=', now()->addDays($days))
                  ->where('status', 'active');
        }

        // Filter by expiry status
        if ($request->filled('expiry')) {
            if ($request->expiry === 'expiring_soon') {
                $query->where('valid_until', '>=', now())
                      ->where('valid_until', '<=', now()->addDays(90))
                      ->where('status', 'active');
            } elseif ($request->expiry === 'expired') {
                $query->where(function($q) {
                    $q->where('status', 'expired')
                      ->orWhere('valid_until', '<', now());
                });
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('holder_name', 'like', "%{$search}%")
                  ->orWhereHas('assessee', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $certificates = $query->paginate(15);

        // Get all schemes for filter dropdown
        $schemes = \App\Models\Scheme::select('id', 'code', 'name')
            ->orderBy('code')
            ->get();

        // Calculate statistics
        $stats = [
            'active' => Certificate::where('status', 'active')->count(),
            'expiring_soon' => Certificate::where('status', 'active')
                ->where('valid_until', '>=', now())
                ->where('valid_until', '<=', now()->addDays(90))
                ->count(),
            'expired' => Certificate::where('status', 'expired')
                ->orWhere('valid_until', '<', now())
                ->count(),
            'revoked' => Certificate::where('status', 'revoked')->count(),
            'renewed' => Certificate::where('status', 'renewed')->count(),
        ];

        return view('admin.certificates.index', compact('certificates', 'schemes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get assessment result from query parameter
        $resultId = $request->get('assessment_result_id');

        $assessmentResult = null;
        $assessmentResultId = null;

        if ($resultId) {
            $assessmentResult = AssessmentResult::with([
                'assessee',
                'scheme',
                'assessment.assessmentUnits'
            ])->findOrFail($resultId);

            // Check if result is competent and approved
            if ($assessmentResult->final_result !== 'competent') {
                return redirect()->route('admin.assessment-results.show', $assessmentResult)
                    ->with('error', 'Hanya assessment result dengan status competent yang dapat diterbitkan sertifikat');
            }

            if ($assessmentResult->approval_status !== 'approved') {
                return redirect()->route('admin.assessment-results.show', $assessmentResult)
                    ->with('error', 'Assessment result harus diapprove terlebih dahulu');
            }

            // Check if certificate already exists
            if (Certificate::where('assessment_result_id', $assessmentResult->id)->exists()) {
                $certificate = Certificate::where('assessment_result_id', $assessmentResult->id)->first();
                return redirect()->route('admin.certificates.show', $certificate)
                    ->with('info', 'Sertifikat untuk assessment result ini sudah ada');
            }

            $assessmentResultId = $resultId;
        }

        // Get all approved competent assessment results that don't have certificates yet
        $assessmentResults = AssessmentResult::with(['assessee', 'scheme'])
            ->where('final_result', 'competent')
            ->where('approval_status', 'approved')
            ->whereDoesntHave('certificate')
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('admin.certificates.create', compact('assessmentResult', 'assessmentResultId', 'assessmentResults'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_result_id' => 'required|exists:assessment_results,id',
            'holder_name' => 'required|string',
            'holder_id_number' => 'nullable|string',
            'issue_date' => 'required|date',
            'valid_from' => 'required|date',
            'validity_period_months' => 'required|integer|min:1|max:60',
            'template_name' => 'nullable|string',
            'language' => 'required|in:id,en',
            'special_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $assessmentResult = AssessmentResult::with(['assessee', 'scheme', 'assessment.assessmentUnits'])
            ->findOrFail($validated['assessment_result_id']);

        // Generate certificate number
        $year = date('Y');
        $lastCertificate = Certificate::where('certificate_number', 'like', "CERT-{$year}-%")
            ->orderBy('certificate_number', 'desc')
            ->first();

        if ($lastCertificate) {
            $lastNumber = intval(substr($lastCertificate->certificate_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['certificate_number'] = "CERT-{$year}-{$newNumber}";
        $validated['registration_number'] = "REG-{$year}-{$newNumber}";

        // Generate QR code and verification URL
        $qrCode = Str::uuid()->toString();
        $validated['qr_code'] = $qrCode;
        $validated['verification_url'] = url("/verify-certificate/{$qrCode}");

        // Set dates
        $validated['valid_until'] = date('Y-m-d', strtotime($validated['valid_from'] . " + {$validated['validity_period_months']} months"));

        // Get unit codes from assessment
        $unitCodes = [];
        if ($assessmentResult->unit_results) {
            foreach ($assessmentResult->unit_results as $unit) {
                if (isset($unit['unit_code'])) {
                    $unitCodes[] = $unit['unit_code'];
                }
            }
        }

        // Fill in data from assessment result
        $validated['assessee_id'] = $assessmentResult->assessee_id;
        $validated['scheme_id'] = $assessmentResult->scheme_id;
        $validated['assessment_date'] = $assessmentResult->created_at;
        $validated['competency_achieved'] = $assessmentResult->scheme->name;
        $validated['unit_codes'] = $unitCodes;
        $validated['scheme_name'] = $assessmentResult->scheme->name;
        $validated['scheme_code'] = $assessmentResult->scheme->code;
        $validated['scheme_level'] = $assessmentResult->certification_level ?? 'Professional';
        $validated['issuing_organization'] = 'LSP-PIE';
        $validated['issuing_organization_license'] = 'LSP-001-BNSP-2024';
        $validated['issuing_organization_address'] = 'Jl. Gatot Subroto No. 123, Jakarta Selatan, Indonesia';
        $validated['status'] = 'active';
        $validated['template_name'] = $validated['template_name'] ?? 'default';
        $validated['is_verified'] = true;
        $validated['is_public'] = true;
        $validated['is_renewable'] = true;
        $validated['issued_by'] = auth()->id();
        $validated['created_by'] = auth()->id();

        // Signatories
        $validated['signatories'] = [
            [
                'name' => 'Dr. Ahmad Budiman',
                'position' => 'Direktur LSP-PIE',
                'signature_path' => '/signatures/director.png'
            ],
            [
                'name' => 'Siti Rahayu, M.Kom',
                'position' => 'Manager Sertifikasi',
                'signature_path' => '/signatures/manager.png'
            ]
        ];

        $certificate = Certificate::create($validated);

        // Create log entry
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'Certificate was created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'source' => 'assessment_result',
                'assessment_result_id' => $assessmentResult->id,
            ],
            'log_level' => 'info',
            'log_category' => 'lifecycle',
        ]);

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificate berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load([
            'assessee',
            'scheme',
            'assessmentResult.assessment',
            'issuedBy',
            'revokedBy',
            'renewedFrom',
            'renewedBy',
            'qrValidations' => function($query) {
                $query->orderBy('scanned_at', 'desc')->limit(10);
            },
            'logs' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(20);
            },
            'revocations',
            'renewals'
        ]);

        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        // Only allow editing if not revoked
        if ($certificate->status === 'revoked') {
            return redirect()->route('admin.certificates.show', $certificate)
                ->with('error', 'Certificate yang sudah direvoke tidak dapat diubah');
        }

        $certificate->load(['assessee', 'scheme', 'assessmentResult']);

        return view('admin.certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        // Only allow editing if not revoked
        if ($certificate->status === 'revoked') {
            return redirect()->route('admin.certificates.show', $certificate)
                ->with('error', 'Certificate yang sudah direvoke tidak dapat diubah');
        }

        $validated = $request->validate([
            'holder_name' => 'required|string',
            'holder_id_number' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'template_name' => 'nullable|string',
            'language' => 'required|in:id,en',
            'special_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        // Store old values for logging
        $oldValues = $certificate->only(['holder_name', 'valid_from', 'valid_until', 'is_public']);

        $validated['updated_by'] = auth()->id();
        $certificate->update($validated);

        // Create log entry
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => 'Certificate was updated',
            'changes' => [
                'before' => $oldValues,
                'after' => $certificate->only(['holder_name', 'valid_from', 'valid_until', 'is_public'])
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'log_level' => 'info',
            'log_category' => 'lifecycle',
        ]);

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificate berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        // Only allow deletion if not issued or used
        if ($certificate->verification_count > 0 || $certificate->qr_scan_count > 0) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Certificate yang sudah diverifikasi tidak dapat dihapus');
        }

        if ($certificate->status === 'revoked') {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Certificate yang sudah direvoke tidak dapat dihapus');
        }

        // Create log entry before deletion
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'description' => 'Certificate was deleted',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'log_level' => 'warning',
            'log_category' => 'lifecycle',
        ]);

        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate berhasil dihapus');
    }

    /**
     * Download certificate PDF
     */
    public function download(Certificate $certificate)
    {
        // TODO: Generate PDF and return download
        // For now, just redirect with message
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('info', 'PDF generation will be implemented');
    }

    /**
     * Generate QR code image
     */
    public function generateQr(Certificate $certificate)
    {
        // TODO: Generate QR code image
        // For now, just redirect with message
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('info', 'QR code generation will be implemented');
    }
}
