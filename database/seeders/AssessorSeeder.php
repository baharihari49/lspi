<?php

namespace Database\Seeders;

use App\Models\Assessor;
use App\Models\AssessorBankInfo;
use App\Models\AssessorCompetencyScope;
use App\Models\AssessorDocument;
use App\Models\AssessorExperience;
use App\Models\MasterDocumentType;
use App\Models\MasterStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessorSeeder extends Seeder
{
    public function run(): void
    {
        $activeStatus = MasterStatus::where('code', 'active')->first();
        $certificateDocType = MasterDocumentType::where('code', 'certificate')->first();

        // Create sample users for assessors
        $users = [];
        $userData = [
            ['name' => 'Dr. Ahmad Fauzi', 'email' => 'ahmad.fauzi@lsp-pie.org'],
            ['name' => 'Siti Nurhaliza, M.Kom', 'email' => 'siti.nurhaliza@lsp-pie.org'],
            ['name' => 'Budi Santoso, S.Kom', 'email' => 'budi.santoso@lsp-pie.org'],
        ];

        foreach ($userData as $data) {
            $users[] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => bcrypt('password123')])
            );
        }

        // Create assessors
        $assessorData = [
            [
                'registration_number' => 'ASR-2023-0001',
                'user_id' => $users[0]->id,
                'full_name' => 'Dr. Ahmad Fauzi',
                'id_card_number' => '3275012345678901',
                'birth_date' => '1980-05-15',
                'birth_place' => 'Bandung',
                'gender' => 'male',
                'address' => 'Jl. Pendidikan No. 123',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40132',
                'phone' => '022-1234567',
                'mobile' => '08123456789',
                'email' => 'ahmad.fauzi@lsp-pie.org',
                'education_level' => 'S3',
                'major' => 'Ilmu Perpustakaan',
                'institution' => 'Universitas Indonesia',
                'occupation' => 'Dosen',
                'company' => 'Universitas Padjadjaran',
                'position' => 'Lektor Kepala',
                'registration_date' => now()->subYears(2),
                'valid_until' => now()->addYear(),
                'met_number' => 'MET.70.01.0.0.1.2023.0001',
                'registration_status' => 'active',
                'verification_status' => 'verified',
                'verified_by' => 1,
                'verified_at' => now()->subMonths(23),
                'is_active' => true,
                'status_id' => $activeStatus?->id,
            ],
            [
                'registration_number' => 'ASR-2023-0002',
                'user_id' => $users[1]->id,
                'full_name' => 'Siti Nurhaliza, M.Kom',
                'id_card_number' => '3173024567890123',
                'birth_date' => '1985-08-20',
                'birth_place' => 'Jakarta',
                'gender' => 'female',
                'address' => 'Jl. Teknologi No. 45',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12950',
                'phone' => '021-9876543',
                'mobile' => '08234567890',
                'email' => 'siti.nurhaliza@lsp-pie.org',
                'education_level' => 'S2',
                'major' => 'Teknologi Informasi',
                'institution' => 'Institut Teknologi Bandung',
                'occupation' => 'Pustakawan',
                'company' => 'Perpustakaan Nasional',
                'position' => 'Pustakawan Madya',
                'registration_date' => now()->subMonths(18),
                'valid_until' => now()->addMonths(6),
                'met_number' => 'MET.70.01.0.0.1.2023.0002',
                'registration_status' => 'active',
                'verification_status' => 'verified',
                'verified_by' => 1,
                'verified_at' => now()->subMonths(17),
                'is_active' => true,
                'status_id' => $activeStatus?->id,
            ],
            [
                'registration_number' => 'ASR-2025-0001',
                'user_id' => $users[2]->id,
                'full_name' => 'Budi Santoso, S.Kom',
                'id_card_number' => '3578036789012345',
                'birth_date' => '1990-12-10',
                'birth_place' => 'Surabaya',
                'gender' => 'male',
                'address' => 'Jl. Ilmu Pengetahuan No. 78',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60119',
                'phone' => '031-5551234',
                'mobile' => '08345678901',
                'email' => 'budi.santoso@lsp-pie.org',
                'education_level' => 'S1',
                'major' => 'Sistem Informasi',
                'institution' => 'Universitas Airlangga',
                'occupation' => 'IT Specialist',
                'company' => 'PT Digital Library Indonesia',
                'position' => 'Senior Developer',
                'registration_date' => now()->subMonths(6),
                'valid_until' => now()->addMonths(18),
                'met_number' => null,
                'registration_status' => 'active',
                'verification_status' => 'pending',
                'is_active' => true,
                'status_id' => $activeStatus?->id,
            ],
        ];

        $assessors = [];
        foreach ($assessorData as $data) {
            $assessors[] = Assessor::create($data);
        }

        // Create competency scopes
        $competencyData = [
            [
                'assessor_id' => $assessors[0]->id,
                'scheme_code' => 'SKM-001',
                'scheme_name' => 'Pengelolaan Jurnal Elektronik',
                'competency_unit_code' => 'M.70PIE01.001.2',
                'competency_unit_title' => 'Mengelola Sistem Jurnal Elektronik',
                'certificate_number' => 'CERT/PIE/2023/001',
                'certificate_issued_date' => now()->subYears(2),
                'certificate_expiry_date' => now()->addYears(3),
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now()->subYears(2)->addDays(7),
                'is_active' => true,
            ],
            [
                'assessor_id' => $assessors[1]->id,
                'scheme_code' => 'SKM-002',
                'scheme_name' => 'Penerapan IT untuk Artikel Ilmiah',
                'competency_unit_code' => 'M.70PIE02.001.2',
                'competency_unit_title' => 'Menerapkan Teknologi Informasi dalam Publikasi Ilmiah',
                'certificate_number' => 'CERT/PIE/2023/002',
                'certificate_issued_date' => now()->subMonths(18),
                'certificate_expiry_date' => now()->addYears(2)->addMonths(6),
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now()->subMonths(18)->addDays(5),
                'is_active' => true,
            ],
        ];

        foreach ($competencyData as $data) {
            AssessorCompetencyScope::create($data);
        }

        // Create experiences
        $experienceData = [
            [
                'assessor_id' => $assessors[0]->id,
                'organization_name' => 'Universitas Padjadjaran',
                'position' => 'Asesor Kompetensi',
                'experience_type' => 'assessment',
                'description' => 'Melakukan asesmen kompetensi untuk skema Pengelolaan Jurnal Elektronik',
                'start_date' => now()->subYears(3),
                'end_date' => null,
                'is_current' => true,
                'location' => 'Bandung',
                'reference_name' => 'Prof. Dr. Suherman',
                'reference_contact' => '08111222333',
            ],
            [
                'assessor_id' => $assessors[1]->id,
                'organization_name' => 'Perpustakaan Nasional RI',
                'position' => 'Instruktur Pelatihan',
                'experience_type' => 'training',
                'description' => 'Memberikan pelatihan pengelolaan perpustakaan digital',
                'start_date' => now()->subYears(5),
                'end_date' => now()->subYears(2),
                'is_current' => false,
                'location' => 'Jakarta',
                'reference_name' => 'Dra. Wulan Sari',
                'reference_contact' => '08222333444',
            ],
        ];

        foreach ($experienceData as $data) {
            AssessorExperience::create($data);
        }

        // Create documents
        if ($certificateDocType) {
            $documentData = [
                [
                    'assessor_id' => $assessors[0]->id,
                    'document_type_id' => $certificateDocType->id,
                    'title' => 'Sertifikat Asesor Kompetensi',
                    'description' => 'Sertifikat Asesor LSP PIE',
                    'document_number' => 'SERT-ASR-2023-001',
                    'issued_date' => now()->subYears(2),
                    'expiry_date' => now()->addYears(3),
                    'verification_status' => 'approved',
                    'verified_by' => 1,
                    'verified_at' => now()->subYears(2)->addDays(3),
                    'status_id' => $activeStatus?->id,
                    'uploaded_by' => 1,
                ],
                [
                    'assessor_id' => $assessors[1]->id,
                    'document_type_id' => $certificateDocType->id,
                    'title' => 'Sertifikat Asesor Kompetensi',
                    'description' => 'Sertifikat Asesor LSP PIE',
                    'document_number' => 'SERT-ASR-2023-002',
                    'issued_date' => now()->subMonths(18),
                    'expiry_date' => now()->addYears(2)->addMonths(6),
                    'verification_status' => 'approved',
                    'verified_by' => 1,
                    'verified_at' => now()->subMonths(18)->addDays(2),
                    'status_id' => $activeStatus?->id,
                    'uploaded_by' => 1,
                ],
            ];

            foreach ($documentData as $data) {
                AssessorDocument::create($data);
            }
        }

        // Create bank info
        $bankData = [
            [
                'assessor_id' => $assessors[0]->id,
                'bank_name' => 'Bank Mandiri',
                'bank_code' => '008',
                'branch_name' => 'Bandung',
                'account_number' => '1370012345678',
                'account_holder_name' => 'Ahmad Fauzi',
                'npwp_number' => '12345678901234567890',
                'tax_name' => 'Ahmad Fauzi',
                'tax_address' => 'Jl. Pendidikan No. 123, Bandung',
                'verification_status' => 'verified',
                'verified_by' => 1,
                'verified_at' => now()->subYears(2),
                'is_primary' => true,
                'is_active' => true,
            ],
            [
                'assessor_id' => $assessors[1]->id,
                'bank_name' => 'Bank BCA',
                'bank_code' => '014',
                'branch_name' => 'Jakarta Pusat',
                'account_number' => '1234567890',
                'account_holder_name' => 'Siti Nurhaliza',
                'npwp_number' => '98765432109876543210',
                'tax_name' => 'Siti Nurhaliza',
                'tax_address' => 'Jl. Teknologi No. 45, Jakarta',
                'verification_status' => 'verified',
                'verified_by' => 1,
                'verified_at' => now()->subMonths(18),
                'is_primary' => true,
                'is_active' => true,
            ],
        ];

        foreach ($bankData as $data) {
            AssessorBankInfo::create($data);
        }
    }
}
