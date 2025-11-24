<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\AssessmentResult;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get assessment results that are competent and approved
        $assessmentResults = AssessmentResult::where('final_result', 'competent')
            ->where('approval_status', 'approved')
            ->get();

        if ($assessmentResults->isEmpty()) {
            $this->command->info('No approved competent assessment results found. Skipping certificate seeding.');
            return;
        }

        $admin = User::where('email', 'admin@lsp-pie.id')->first();

        $year = date('Y');
        $certificateNumber = 1;

        foreach ($assessmentResults as $result) {
            // Generate unique identifiers
            $certNumber = sprintf('CERT-%s-%04d', $year, $certificateNumber++);
            $qrCode = Str::uuid()->toString();
            $verificationUrl = url("/verify-certificate/{$qrCode}");

            // Calculate validity dates
            $issueDate = now();
            $validFrom = now();
            $validUntil = now()->addMonths(36); // 3 years validity

            // Get unit codes from assessment result
            $unitCodes = [];
            if ($result->unit_results) {
                foreach ($result->unit_results as $unit) {
                    if (isset($unit['unit_code'])) {
                        $unitCodes[] = $unit['unit_code'];
                    }
                }
            }

            // Create certificate
            Certificate::create([
                'assessment_result_id' => $result->id,
                'assessee_id' => $result->assessee_id,
                'scheme_id' => $result->scheme_id,
                'issued_by' => $admin?->id,
                'certificate_number' => $certNumber,
                'registration_number' => sprintf('REG-%s-%04d', $year, $certificateNumber - 1),
                'qr_code' => $qrCode,
                'verification_url' => $verificationUrl,
                'status' => rand(1, 10) > 8 ? 'expired' : 'active', // 20% expired
                'holder_name' => $result->assessee->full_name,
                'holder_id_number' => $result->assessee->id_number,
                'competency_achieved' => $result->scheme->name,
                'unit_codes' => $unitCodes,
                'scheme_name' => $result->scheme->name,
                'scheme_code' => $result->scheme->code,
                'scheme_level' => $result->certification_level ?? 'Professional',
                'issuing_organization' => 'LSP-PIE',
                'issuing_organization_license' => 'LSP-001-BNSP-2024',
                'issuing_organization_address' => 'Jl. Contoh No. 123, Jakarta, Indonesia',
                'assessment_date' => $result->created_at,
                'issue_date' => $issueDate,
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'validity_period_months' => 36,
                'signatories' => [
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
                ],
                'template_name' => 'default',
                'language' => 'id',
                'custom_fields' => null,
                'pdf_path' => "/certificates/{$certNumber}.pdf",
                'pdf_url' => url("/storage/certificates/{$certNumber}.pdf"),
                'pdf_file_size' => rand(200000, 500000),
                'pdf_generated_at' => $issueDate,
                'qr_code_path' => "/qr-codes/{$qrCode}.png",
                'qr_scan_count' => rand(0, 50),
                'is_verified' => true,
                'is_public' => true,
                'verification_count' => rand(0, 100),
                'renewed_from_certificate_id' => null,
                'renewed_by_certificate_id' => null,
                'is_renewable' => true,
                'renewal_notification_sent_at' => null,
                'revoked_at' => null,
                'revoked_by' => null,
                'revocation_reason' => null,
                'notes' => null,
                'special_conditions' => null,
                'metadata' => [
                    'generated_by' => 'system',
                    'certificate_version' => '1.0'
                ],
                'created_by' => $admin?->id,
                'updated_by' => null,
            ]);
        }

        $this->command->info('✓ Created ' . $certificateNumber - 1 . ' certificates');

        // Create some QR validation records
        $this->createQrValidations();

        // Create some certificate logs
        $this->createCertificateLogs();
    }

    private function createQrValidations(): void
    {
        $certificates = Certificate::limit(5)->get();

        foreach ($certificates as $certificate) {
            $scanCount = rand(1, 10);

            for ($i = 0; $i < $scanCount; $i++) {
                \App\Models\CertificateQrValidation::create([
                    'certificate_id' => $certificate->id,
                    'scan_id' => Str::uuid()->toString(),
                    'scanned_at' => now()->subDays(rand(1, 90)),
                    'scan_method' => 'qr_code',
                    'validation_status' => $certificate->isValid() ? 'valid' : ($certificate->isExpired() ? 'expired' : 'revoked'),
                    'scanner_ip' => fake()->ipv4(),
                    'scanner_user_agent' => fake()->userAgent(),
                    'scanner_device_type' => fake()->randomElement(['mobile', 'desktop', 'tablet']),
                    'scanner_location' => [
                        'country' => 'Indonesia',
                        'city' => fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta']),
                        'lat' => fake()->latitude(-10, 5),
                        'lng' => fake()->longitude(95, 141),
                    ],
                    'referrer_url' => fake()->url(),
                    'source' => 'website',
                    'validation_response' => [
                        'certificate_number' => $certificate->certificate_number,
                        'holder_name' => $certificate->holder_name,
                        'status' => $certificate->status,
                        'valid_until' => $certificate->valid_until->format('Y-m-d'),
                    ],
                    'response_time_ms' => rand(50, 500),
                    'is_suspicious' => false,
                    'suspicious_reason' => null,
                ]);
            }
        }

        $this->command->info('✓ Created QR validation records');
    }

    private function createCertificateLogs(): void
    {
        $certificates = Certificate::limit(5)->get();
        $admin = User::where('email', 'admin@lsp-pie.id')->first();

        foreach ($certificates as $certificate) {
            // Log: Certificate created
            \App\Models\CertificateLog::create([
                'certificate_id' => $certificate->id,
                'user_id' => $admin?->id,
                'action' => 'created',
                'description' => 'Certificate was created',
                'changes' => null,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'metadata' => [
                    'source' => 'assessment_result',
                    'assessment_result_id' => $certificate->assessment_result_id,
                ],
                'log_level' => 'info',
                'log_category' => 'lifecycle',
            ]);

            // Log: Certificate issued
            \App\Models\CertificateLog::create([
                'certificate_id' => $certificate->id,
                'user_id' => $admin?->id,
                'action' => 'issued',
                'description' => 'Certificate was issued to ' . $certificate->holder_name,
                'changes' => null,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'metadata' => [
                    'issue_date' => $certificate->issue_date->format('Y-m-d'),
                ],
                'log_level' => 'info',
                'log_category' => 'lifecycle',
            ]);

            // Log: Certificate verified (random occurrences)
            $verificationCount = rand(0, 5);
            for ($i = 0; $i < $verificationCount; $i++) {
                \App\Models\CertificateLog::create([
                    'certificate_id' => $certificate->id,
                    'user_id' => null,
                    'action' => 'verified',
                    'description' => 'Certificate was verified online',
                    'changes' => null,
                    'ip_address' => fake()->ipv4(),
                    'user_agent' => fake()->userAgent(),
                    'metadata' => [
                        'verification_method' => 'qr_code',
                    ],
                    'log_level' => 'info',
                    'log_category' => 'verification',
                ]);
            }
        }

        $this->command->info('✓ Created certificate logs');
    }
}
