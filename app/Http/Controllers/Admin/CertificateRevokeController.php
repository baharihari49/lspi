<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateRevoke;
use App\Models\Certificate;
use App\Models\CertificateLog;
use Illuminate\Http\Request;

class CertificateRevokeController extends Controller
{
    public function index(Request $request)
    {
        $query = CertificateRevoke::with(['certificate.assessee', 'revokedBy', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('revocation_reason_category')) {
            $query->where('revocation_reason_category', $request->revocation_reason_category);
        }

        $revocations = $query->paginate(15);

        return view('admin.certificate-revoke.index', compact('revocations'));
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
                ->with('error', 'Certificate sudah direvoke');
        }

        return view('admin.certificate-revoke.create', compact('certificate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_id' => 'required|exists:certificates,id',
            'revocation_reason_category' => 'required|in:holder_request,certification_withdrawn,fraud_misconduct,competency_loss,non_compliance,superseded,administrative,other',
            'revocation_reason' => 'required|string',
            'revocation_date' => 'required|date',
            'is_appealable' => 'required|boolean',
            'public_notification_required' => 'required|boolean',
        ]);

        $year = date('Y');
        $lastRevoke = CertificateRevoke::where('revocation_number', 'like', "REV-{$year}-%")
            ->orderBy('revocation_number', 'desc')
            ->first();

        if ($lastRevoke) {
            $lastNumber = intval(substr($lastRevoke->revocation_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['revocation_number'] = "REV-{$year}-{$newNumber}";
        $validated['revocation_request_date'] = now();
        $validated['revoked_by'] = auth()->id();
        $validated['status'] = 'pending';

        $revocation = CertificateRevoke::create($validated);

        return redirect()->route('admin.certificate-revoke.show', $revocation)
            ->with('success', 'Revocation request berhasil dibuat');
    }

    public function show(CertificateRevoke $certificateRevoke)
    {
        $certificateRevoke->load([
            'certificate.assessee',
            'certificate.scheme',
            'revokedBy',
            'approvedBy'
        ]);

        return view('admin.certificate-revoke.show', compact('certificateRevoke'));
    }

    public function edit(CertificateRevoke $certificateRevoke)
    {
        // Only allow editing if still pending
        if ($certificateRevoke->status !== 'pending') {
            return redirect()->route('admin.certificate-revoke.show', $certificateRevoke)
                ->with('error', 'Hanya revocation dengan status pending yang dapat diubah');
        }

        $certificateRevoke->load(['certificate.assessee', 'certificate.scheme']);

        return view('admin.certificate-revoke.edit', compact('certificateRevoke'));
    }

    public function update(Request $request, CertificateRevoke $certificateRevoke)
    {
        // Only allow editing if still pending
        if ($certificateRevoke->status !== 'pending') {
            return redirect()->route('admin.certificate-revoke.show', $certificateRevoke)
                ->with('error', 'Hanya revocation dengan status pending yang dapat diubah');
        }

        $validated = $request->validate([
            'revocation_reason_category' => 'required|in:holder_request,certification_withdrawn,fraud_misconduct,competency_loss,non_compliance,superseded,administrative,other',
            'revocation_reason' => 'required|string',
            'revocation_date' => 'required|date',
            'is_appealable' => 'required|boolean',
            'appeal_deadline' => 'nullable|date|after:revocation_date',
            'public_notification_required' => 'required|boolean',
            'impact_notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $certificateRevoke->update($validated);

        return redirect()->route('admin.certificate-revoke.show', $certificateRevoke)
            ->with('success', 'Revocation berhasil diupdate');
    }

    public function destroy(CertificateRevoke $certificateRevoke)
    {
        // Only allow deletion if still pending
        if ($certificateRevoke->status !== 'pending') {
            return redirect()->route('admin.certificate-revoke.index')
                ->with('error', 'Hanya revocation dengan status pending yang dapat dihapus');
        }

        $certificateRevoke->delete();

        return redirect()->route('admin.certificate-revoke.index')
            ->with('success', 'Revocation berhasil dihapus');
    }

    public function approve(CertificateRevoke $certificateRevoke)
    {
        if ($certificateRevoke->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya revocation dengan status pending yang dapat diapprove');
        }

        $certificateRevoke->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'revocation_approval_date' => now(),
        ]);

        // Update certificate status
        $certificate = $certificateRevoke->certificate;
        $certificate->update([
            'status' => 'revoked',
            'revoked_at' => $certificateRevoke->revocation_date,
            'revoked_by' => $certificateRevoke->revoked_by,
            'revocation_reason' => $certificateRevoke->revocation_reason,
        ]);

        // Create log
        CertificateLog::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'action' => 'revoked',
            'description' => 'Certificate was revoked',
            'metadata' => [
                'revocation_id' => $certificateRevoke->id,
                'reason_category' => $certificateRevoke->revocation_reason_category,
            ],
            'log_level' => 'warning',
            'log_category' => 'security',
        ]);

        return redirect()->route('admin.certificate-revoke.show', $certificateRevoke)
            ->with('success', 'Revocation berhasil diapprove');
    }

    public function reject(Request $request, CertificateRevoke $certificateRevoke)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $certificateRevoke->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'revocation_approval_date' => now(),
            'internal_notes' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.certificate-revoke.show', $certificateRevoke)
            ->with('success', 'Revocation berhasil direject');
    }
}
