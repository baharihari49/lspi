<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateRenewal;
use App\Models\Certificate;
use App\Models\CertificateLog;
use Illuminate\Http\Request;

class CertificateRenewalController extends Controller
{
    public function index(Request $request)
    {
        $query = CertificateRenewal::with(['originalCertificate.assessee', 'newCertificate', 'assessee', 'scheme'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('renewal_type')) {
            $query->where('renewal_type', $request->renewal_type);
        }

        $renewals = $query->paginate(15);

        return view('admin.certificate-renewal.index', compact('renewals'));
    }

    public function create(Request $request)
    {
        $certificateId = $request->get('certificate_id');

        if (!$certificateId) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Certificate ID diperlukan');
        }

        $certificate = Certificate::with(['assessee', 'scheme'])->findOrFail($certificateId);

        if ($certificate->status === 'revoked') {
            return redirect()->route('admin.certificates.show', $certificate)
                ->with('error', 'Certificate yang direvoke tidak dapat direnew');
        }

        if (!$certificate->is_renewable) {
            return redirect()->route('admin.certificates.show', $certificate)
                ->with('error', 'Certificate ini tidak dapat direnew');
        }

        return view('admin.certificate-renewal.create', compact('certificate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_certificate_id' => 'required|exists:certificates,id',
            'renewal_type' => 'required|in:standard,simplified,automatic,early_renewal,late_renewal,grace_period_renewal',
            'renewal_due_date' => 'nullable|date',
            'requires_reassessment' => 'required|boolean',
            'cpd_required' => 'required|boolean',
            'cpd_hours_required' => 'nullable|integer|min:0',
            'renewal_fee' => 'nullable|numeric|min:0',
        ]);

        $certificate = Certificate::findOrFail($validated['original_certificate_id']);

        $year = date('Y');
        $lastRenewal = CertificateRenewal::where('renewal_number', 'like', "REN-{$year}-%")
            ->orderBy('renewal_number', 'desc')
            ->first();

        if ($lastRenewal) {
            $lastNumber = intval(substr($lastRenewal->renewal_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['renewal_number'] = "REN-{$year}-{$newNumber}";
        $validated['assessee_id'] = $certificate->assessee_id;
        $validated['scheme_id'] = $certificate->scheme_id;
        $validated['renewal_request_date'] = now();
        $validated['original_expiry_date'] = $certificate->valid_until;
        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        $renewal = CertificateRenewal::create($validated);

        // Create log
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'renewal_requested',
            'description' => 'Certificate renewal was requested',
            'metadata' => [
                'renewal_id' => $renewal->id,
                'renewal_type' => $renewal->renewal_type,
            ],
            'log_level' => 'info',
            'log_category' => 'lifecycle',
        ]);

        return redirect()->route('admin.certificate-renewal.show', $renewal)
            ->with('success', 'Renewal request berhasil dibuat');
    }

    public function show(CertificateRenewal $certificateRenewal)
    {
        $certificateRenewal->load([
            'originalCertificate.assessee',
            'originalCertificate.scheme',
            'newCertificate',
            'requestedBy',
            'processedBy',
            'renewalAssessment'
        ]);

        return view('admin.certificate-renewal.show', compact('certificateRenewal'));
    }

    public function edit(CertificateRenewal $certificateRenewal)
    {
        // Only allow editing if still pending
        if (!in_array($certificateRenewal->status, ['pending', 'in_assessment'])) {
            return redirect()->route('admin.certificate-renewal.show', $certificateRenewal)
                ->with('error', 'Hanya renewal dengan status pending atau in_assessment yang dapat diubah');
        }

        $certificateRenewal->load([
            'originalCertificate.assessee',
            'originalCertificate.scheme'
        ]);

        return view('admin.certificate-renewal.edit', compact('certificateRenewal'));
    }

    public function update(Request $request, CertificateRenewal $certificateRenewal)
    {
        // Only allow editing if still pending
        if (!in_array($certificateRenewal->status, ['pending', 'in_assessment'])) {
            return redirect()->route('admin.certificate-renewal.show', $certificateRenewal)
                ->with('error', 'Hanya renewal dengan status pending atau in_assessment yang dapat diubah');
        }

        $validated = $request->validate([
            'renewal_type' => 'required|in:standard,simplified,automatic,early_renewal,late_renewal,grace_period_renewal',
            'renewal_due_date' => 'nullable|date',
            'requires_reassessment' => 'required|boolean',
            'cpd_required' => 'required|boolean',
            'cpd_hours_required' => 'nullable|integer|min:0',
            'cpd_hours_completed' => 'nullable|integer|min:0',
            'renewal_fee' => 'nullable|numeric|min:0',
            'fee_paid' => 'required|boolean',
            'fee_paid_date' => 'nullable|date',
            'payment_reference' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'notes_for_assessee' => 'nullable|string',
        ]);

        $certificateRenewal->update($validated);

        return redirect()->route('admin.certificate-renewal.show', $certificateRenewal)
            ->with('success', 'Renewal berhasil diupdate');
    }

    public function destroy(CertificateRenewal $certificateRenewal)
    {
        // Only allow deletion if still pending
        if ($certificateRenewal->status !== 'pending') {
            return redirect()->route('admin.certificate-renewal.index')
                ->with('error', 'Hanya renewal dengan status pending yang dapat dihapus');
        }

        $certificateRenewal->delete();

        return redirect()->route('admin.certificate-renewal.index')
            ->with('success', 'Renewal berhasil dihapus');
    }

    public function approve(Request $request, CertificateRenewal $certificateRenewal)
    {
        $validated = $request->validate([
            'extension_months' => 'required|integer|min:1|max:60',
            'decision_notes' => 'nullable|string',
        ]);

        if ($certificateRenewal->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya renewal dengan status pending yang dapat diapprove');
        }

        $newExpiryDate = date('Y-m-d', strtotime($certificateRenewal->original_expiry_date . " + {$validated['extension_months']} months"));

        $certificateRenewal->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'renewal_processed_date' => now(),
            'new_expiry_date' => $newExpiryDate,
            'extension_months' => $validated['extension_months'],
            'decision_notes' => $validated['decision_notes'],
        ]);

        // Update original certificate
        $certificate = $certificateRenewal->originalCertificate;
        $certificate->update([
            'status' => 'renewed',
            'valid_until' => $newExpiryDate,
        ]);

        // Create log
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'renewed',
            'description' => 'Certificate was renewed',
            'changes' => [
                'before' => ['valid_until' => $certificateRenewal->original_expiry_date],
                'after' => ['valid_until' => $newExpiryDate],
            ],
            'metadata' => [
                'renewal_id' => $certificateRenewal->id,
                'extension_months' => $validated['extension_months'],
            ],
            'log_level' => 'info',
            'log_category' => 'lifecycle',
        ]);

        return redirect()->route('admin.certificate-renewal.show', $certificateRenewal)
            ->with('success', 'Renewal berhasil diapprove');
    }

    public function reject(Request $request, CertificateRenewal $certificateRenewal)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $certificateRenewal->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'renewal_processed_date' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.certificate-renewal.show', $certificateRenewal)
            ->with('success', 'Renewal berhasil direject');
    }
}
