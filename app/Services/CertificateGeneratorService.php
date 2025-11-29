<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Certificate;
use App\Models\Apl01Form;
use App\Models\Scheme;
use Illuminate\Support\Str;

class CertificateGeneratorService
{
    /**
     * Default certificate validity in months.
     */
    protected int $validityMonths = 36; // 3 years

    /**
     * Check if certificate can be generated for an assessment.
     */
    public function canGenerateCertificate(Assessment $assessment): bool
    {
        // Assessment must be approved
        if ($assessment->status !== 'approved') {
            return false;
        }

        // Overall result must be competent
        if ($assessment->overall_result !== 'competent') {
            return false;
        }

        // Certificate should not already exist
        return !$this->hasCertificate($assessment);
    }

    /**
     * Check if certificate already exists for assessment.
     */
    public function hasCertificate(Assessment $assessment): bool
    {
        // Get the assessment result for this assessment
        $assessmentResult = AssessmentResult::where('assessment_id', $assessment->id)->first();

        if (!$assessmentResult) {
            return false;
        }

        return Certificate::where('assessment_result_id', $assessmentResult->id)->exists();
    }

    /**
     * Generate certificate from approved assessment.
     */
    public function generateFromAssessment(Assessment $assessment): Certificate
    {
        if (!$this->canGenerateCertificate($assessment)) {
            throw new \Exception('Cannot generate certificate: Assessment is not eligible.');
        }

        // Get the assessment result for this assessment
        $assessmentResult = AssessmentResult::where('assessment_id', $assessment->id)->first();

        if (!$assessmentResult) {
            throw new \Exception('Cannot generate certificate: Assessment result not found.');
        }

        $apl01 = $assessment->apl01Form;
        $assessee = $assessment->assessee;
        $scheme = $assessment->scheme;

        $certificate = Certificate::create([
            'assessment_result_id' => $assessmentResult->id,
            'assessee_id' => $assessment->assessee_id,
            'scheme_id' => $assessment->scheme_id,
            'issued_by' => auth()->id() ?? 1,
            'certificate_number' => $this->generateCertificateNumber($scheme),
            'registration_number' => $this->generateRegistrationNumber(),
            'qr_code' => $this->generateQrCode(),
            'verification_url' => $this->generateVerificationUrl(),
            'status' => 'active',
            'holder_name' => $assessee->full_name ?? $apl01->full_name ?? '',
            'holder_id_number' => $assessee->id_number ?? $apl01->id_number ?? '',
            'competency_achieved' => $this->getCompetencyAchieved($assessment),
            'unit_codes' => $this->getUnitCodes($assessment),
            'scheme_name' => $scheme->name ?? '',
            'scheme_code' => $scheme->code ?? '',
            'scheme_level' => $scheme->level ?? '',
            'issuing_organization' => config('app.lsp_name', 'LSP Pustaka Ilmiah Elektronik'),
            'issuing_organization_license' => config('app.lsp_license', 'KEP-XXXXX/BNSP/XX/XXXX'),
            'issuing_organization_address' => config('app.lsp_address', ''),
            'assessment_date' => $assessment->scheduled_date,
            'issue_date' => now(),
            'valid_from' => now(),
            'valid_until' => now()->addMonths($this->validityMonths),
            'validity_period_months' => $this->validityMonths,
            'signatories' => $this->getSignatories(),
            'template_name' => 'default',
            'language' => 'id',
            'is_verified' => true,
            'is_public' => true,
            'is_renewable' => true,
            'auto_generated' => true,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Update APL-01 flow status if exists
        if ($apl01) {
            $apl01->updateFlowStatus('certificate_issued');
        }

        return $certificate;
    }

    /**
     * Generate unique certificate number.
     */
    protected function generateCertificateNumber(?Scheme $scheme = null): string
    {
        $year = date('Y');
        $schemeCode = $scheme ? strtoupper(substr($scheme->code ?? 'GEN', 0, 3)) : 'GEN';

        $lastCert = Certificate::where('certificate_number', 'like', "CERT-{$schemeCode}-{$year}-%")
            ->orderBy('certificate_number', 'desc')
            ->first();

        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'CERT-' . $schemeCode . '-' . $year . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate registration number.
     */
    protected function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $month = date('m');

        $count = Certificate::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        return 'REG/' . $year . '/' . $month . '/' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate QR code identifier.
     */
    protected function generateQrCode(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Generate verification URL.
     */
    protected function generateVerificationUrl(): string
    {
        $qrCode = $this->generateQrCode();
        return config('app.url') . '/verify/' . $qrCode;
    }

    /**
     * Get competency achieved description.
     */
    protected function getCompetencyAchieved(Assessment $assessment): string
    {
        $scheme = $assessment->scheme;
        return $scheme ? ($scheme->name . ' - ' . ($scheme->description ?? '')) : 'Kompetensi Sertifikasi';
    }

    /**
     * Get unit codes from assessment.
     */
    protected function getUnitCodes(Assessment $assessment): array
    {
        // Get from assessment units or APL-02 units
        $apl01 = $assessment->apl01Form;

        if ($apl01) {
            return $apl01->apl02Units()
                ->where('assessment_result', 'competent')
                ->pluck('unit_code')
                ->toArray();
        }

        // Fallback to scheme units
        $scheme = $assessment->scheme;
        if ($scheme) {
            return $scheme->units()->pluck('code')->toArray();
        }

        return [];
    }

    /**
     * Get default signatories.
     */
    protected function getSignatories(): array
    {
        return [
            [
                'name' => config('app.lsp_director_name', 'Direktur LSP PIE'),
                'title' => 'Direktur',
                'signature_path' => null,
            ],
            [
                'name' => config('app.lsp_manager_name', 'Manager Sertifikasi'),
                'title' => 'Manager Sertifikasi',
                'signature_path' => null,
            ],
        ];
    }

    /**
     * Set validity period in months.
     */
    public function setValidityMonths(int $months): self
    {
        $this->validityMonths = $months;
        return $this;
    }

    /**
     * Check if assessment result is eligible for certificate.
     */
    public function isAssessmentResultEligible(AssessmentResult $result): bool
    {
        return $result->decision === 'competent'
            && $result->status === 'approved';
    }
}
