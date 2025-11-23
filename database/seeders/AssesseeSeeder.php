<?php

namespace Database\Seeders;

use App\Models\Assessee;
use App\Models\AssesseeDocument;
use App\Models\AssesseeEmploymentInfo;
use App\Models\AssesseeEducationHistory;
use App\Models\AssesseeExperience;
use App\Models\User;
use App\Models\MasterStatus;
use App\Models\MasterDocumentType;
use Illuminate\Database\Seeder;

class AssesseeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->take(5)->get();
        $documentTypes = MasterDocumentType::all();

        if ($users->isEmpty()) {
            $this->command->warn('No active users found. Skipping AssesseeSeeder.');
            return;
        }

        foreach ($users as $index => $user) {
            // Create Assessee
            $assessee = Assessee::create([
                'user_id' => $user->id,
                'registration_number' => 'ASE-2025-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'full_name' => $user->name,
                'id_number' => '317' . rand(1000000000, 9999999999),
                'id_type' => 'ktp',
                'place_of_birth' => ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang'][$index % 5],
                'date_of_birth' => now()->subYears(25 + $index * 3)->subMonths(rand(1, 11)),
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'marital_status' => ['single', 'married', 'married', 'single', 'married'][$index % 5],
                'nationality' => 'Indonesia',
                'phone' => '021' . rand(10000000, 99999999),
                'mobile' => '08' . rand(1000000000, 9999999999),
                'email' => $user->email,
                'address' => 'Jl. Contoh No. ' . rand(1, 100) . ', RT ' . rand(1, 10) . '/RW ' . rand(1, 10),
                'city' => ['Jakarta Selatan', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang'][$index % 5],
                'province' => ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah'][$index % 5],
                'postal_code' => '1' . rand(1000, 9999),
                'current_company' => ['PT Teknologi Indonesia', 'CV Digital Media', 'Universitas Negeri', 'PT Media Publishing', 'Yayasan Pendidikan'][$index % 5],
                'current_position' => ['Editor', 'Librarian', 'Academic Staff', 'Publishing Manager', 'Information Specialist'][$index % 5],
                'current_industry' => ['Publishing', 'Education', 'Information Technology', 'Library', 'Academic'][$index % 5],
                'emergency_contact_name' => 'Emergency Contact ' . ($index + 1),
                'emergency_contact_relation' => ['Spouse', 'Parent', 'Sibling', 'Friend', 'Relative'][$index % 5],
                'emergency_contact_phone' => '08' . rand(1000000000, 9999999999),
                'bio' => 'Professional with ' . (5 + $index * 2) . ' years of experience in academic publishing and library management.',
                'verification_status' => $index < 3 ? 'verified' : 'pending',
                'verified_at' => $index < 3 ? now()->subDays(rand(1, 30)) : null,
                'verified_by' => $index < 3 ? 1 : null,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // Add Documents
            if ($documentTypes->isNotEmpty()) {
                foreach ($documentTypes->take(2) as $docIndex => $docType) {
                    AssesseeDocument::create([
                        'assessee_id' => $assessee->id,
                        'document_type_id' => $docType->id,
                        'document_name' => $docType->name . ' - ' . $assessee->full_name,
                        'document_number' => 'DOC-' . rand(100000, 999999),
                        'description' => 'Document for ' . $docType->name,
                        'file_path' => 'assessee-documents/sample-doc-' . $assessee->id . '-' . $docIndex . '.pdf',
                        'file_name' => 'sample-doc.pdf',
                        'file_type' => 'application/pdf',
                        'file_size' => rand(100000, 1000000),
                        'verification_status' => $index < 3 ? 'verified' : 'pending',
                        'verified_at' => $index < 3 ? now()->subDays(rand(1, 20)) : null,
                        'verified_by' => $index < 3 ? 1 : null,
                        'issue_date' => now()->subYears(rand(1, 5)),
                        'expiry_date' => now()->addYears(rand(1, 5)),
                        'issuing_authority' => ['Kemendikbud', 'BAN-PT', 'LIPI', 'Kementerian', 'Lembaga Sertifikasi'][$index % 5],
                        'is_required' => $docIndex == 0,
                        'is_public' => false,
                        'order' => $docIndex,
                        'uploaded_by' => 1,
                    ]);
                }
            }

            // Add Employment History
            $employmentData = [
                [
                    'company_name' => 'PT Teknologi Indonesia',
                    'position_title' => 'Senior Editor',
                    'employment_type' => 'full_time',
                    'is_current' => true,
                    'start_date' => now()->subYears(3),
                ],
                [
                    'company_name' => 'CV Digital Media',
                    'position_title' => 'Editor',
                    'employment_type' => 'full_time',
                    'is_current' => false,
                    'start_date' => now()->subYears(6),
                    'end_date' => now()->subYears(3),
                ],
            ];

            foreach ($employmentData as $empIndex => $empData) {
                AssesseeEmploymentInfo::create(array_merge($empData, [
                    'assessee_id' => $assessee->id,
                    'company_industry' => 'Publishing',
                    'company_location' => ['Jakarta', 'Bandung', 'Surabaya'][$index % 3],
                    'department' => 'Editorial',
                    'job_description' => 'Managing editorial processes and content quality',
                    'responsibilities' => 'Editorial management, content review, quality assurance',
                    'achievements' => 'Published 50+ academic papers, improved workflow efficiency',
                    'skills_used' => 'Editorial management, proofreading, academic publishing',
                    'supervisor_name' => 'Supervisor Name',
                    'supervisor_title' => 'Editor in Chief',
                    'supervisor_contact' => '021' . rand(10000000, 99999999),
                    'verification_status' => $index < 3 ? 'verified' : 'pending',
                    'verified_at' => $index < 3 ? now()->subDays(rand(1, 20)) : null,
                    'verified_by' => $index < 3 ? 1 : null,
                    'order' => $empIndex,
                ]));
            }

            // Add Education History
            $educationData = [
                [
                    'institution_name' => 'Universitas Indonesia',
                    'education_level' => 's2',
                    'degree_name' => 'Master of Library Science',
                    'major' => 'Library and Information Science',
                    'start_date' => now()->subYears(8),
                    'end_date' => now()->subYears(6),
                    'gpa' => '3.75',
                    'honors' => 'Cum Laude',
                ],
                [
                    'institution_name' => 'Universitas Gadjah Mada',
                    'education_level' => 's1',
                    'degree_name' => 'Bachelor of Library Science',
                    'major' => 'Library Science',
                    'start_date' => now()->subYears(12),
                    'end_date' => now()->subYears(8),
                    'gpa' => '3.50',
                ],
            ];

            foreach ($educationData as $eduIndex => $eduData) {
                AssesseeEducationHistory::create(array_merge($eduData, [
                    'assessee_id' => $assessee->id,
                    'institution_location' => ['Jakarta', 'Yogyakarta'][$eduIndex],
                    'institution_type' => 'University',
                    'is_current' => false,
                    'gpa_scale' => '4.0',
                    'achievements' => 'Academic excellence award',
                    'graduation_date' => $eduData['end_date'],
                    'verification_status' => $index < 3 ? 'verified' : 'pending',
                    'verified_at' => $index < 3 ? now()->subDays(rand(1, 20)) : null,
                    'verified_by' => $index < 3 ? 1 : null,
                    'order' => $eduIndex,
                ]));
            }

            // Add Experiences
            $experienceTypes = ['professional', 'certification', 'publication', 'training', 'award'];
            foreach ($experienceTypes as $expIndex => $expType) {
                $expData = [
                    'assessee_id' => $assessee->id,
                    'experience_type' => $expType,
                    'title' => ucfirst($expType) . ' Experience ' . ($expIndex + 1),
                    'organization' => 'Organization ' . ($expIndex + 1),
                    'location' => ['Jakarta', 'Bandung', 'Online'][$expIndex % 3],
                    'description' => 'Description of ' . $expType . ' experience',
                    'start_date' => now()->subYears(rand(1, 5)),
                    'is_ongoing' => $expIndex == 0,
                    'verification_status' => $index < 3 ? 'verified' : 'pending',
                    'verified_at' => $index < 3 ? now()->subDays(rand(1, 20)) : null,
                    'verified_by' => $index < 3 ? 1 : null,
                    'relevance_to_certification' => 'Highly relevant to certification requirements',
                    'relevance_score' => rand(7, 10),
                    'order' => $expIndex,
                ];

                // Add type-specific fields
                if ($expType === 'certification') {
                    $expData['certificate_number'] = 'CERT-' . rand(100000, 999999);
                    $expData['issuing_organization'] = 'Professional Body';
                    $expData['issue_date'] = now()->subYears(rand(1, 3));
                    $expData['expiry_date'] = now()->addYears(rand(1, 3));
                } elseif ($expType === 'publication') {
                    $expData['publication_title'] = 'Academic Publication Title';
                    $expData['publisher'] = 'Academic Publisher';
                    $expData['publication_date'] = now()->subYears(rand(1, 3));
                    $expData['doi'] = '10.1000/example.' . rand(1000, 9999);
                } elseif ($expType === 'award') {
                    $expData['award_name'] = 'Excellence Award';
                    $expData['award_issuer'] = 'Professional Association';
                    $expData['award_date'] = now()->subYears(rand(1, 3));
                }

                AssesseeExperience::create($expData);
            }
        }

        $this->command->info('✓ Created ' . $users->count() . ' assessees with complete data');
        $this->command->info('  - Each assessee has:');
        $this->command->info('    • 2 documents');
        $this->command->info('    • 2 employment records');
        $this->command->info('    • 2 education records');
        $this->command->info('    • 5 experience records (various types)');
    }
}
