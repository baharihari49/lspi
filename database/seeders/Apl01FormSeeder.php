<?php

namespace Database\Seeders;

use App\Models\Apl01Form;
use App\Models\Apl01Review;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class Apl01FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scheme = Scheme::first();
        $event = Event::first();
        $assessees = Assessee::limit(5)->get();
        $users = User::limit(3)->get();

        if (!$scheme || $assessees->count() === 0 || $users->count() === 0) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        $this->command->info('Creating sample APL-01 forms...');

        $forms = [];

        // Form 1: Draft
        $form1 = Apl01Form::create([
            'assessee_id' => $assessees[0]->id,
            'scheme_id' => $scheme->id,
            'event_id' => $event?->id,
            'form_number' => 'APL-01-2025-0001',
            'submission_date' => now(),
            'full_name' => $assessees[0]->full_name,
            'id_number' => $assessees[0]->id_number,
            'date_of_birth' => $assessees[0]->date_of_birth,
            'place_of_birth' => $assessees[0]->place_of_birth,
            'gender' => $assessees[0]->gender,
            'nationality' => $assessees[0]->nationality ?? 'Indonesia',
            'email' => $assessees[0]->email,
            'mobile' => $assessees[0]->mobile,
            'phone' => $assessees[0]->phone,
            'address' => $assessees[0]->address,
            'city' => $assessees[0]->city,
            'province' => $assessees[0]->province,
            'postal_code' => $assessees[0]->postal_code,
            'current_company' => $assessees[0]->current_company,
            'current_position' => $assessees[0]->current_position,
            'current_industry' => $assessees[0]->current_industry,
            'certification_purpose' => 'Meningkatkan kompetensi profesional di bidang perpustakaan digital',
            'target_competency' => 'Pengelolaan sistem perpustakaan elektronik',
            'status' => 'draft',
            'declaration_agreed' => false,
            'is_active' => true,
            'created_by' => 1,
        ]);
        $forms[] = $form1;

        // Form 2: Submitted and Under Review
        if ($assessees->count() > 1) {
            $form2 = Apl01Form::create([
                'assessee_id' => $assessees[1]->id,
                'scheme_id' => $scheme->id,
                'event_id' => $event?->id,
                'form_number' => 'APL-01-2025-0002',
                'submission_date' => now()->subDays(5),
                'full_name' => $assessees[1]->full_name,
                'id_number' => $assessees[1]->id_number,
                'date_of_birth' => $assessees[1]->date_of_birth,
                'place_of_birth' => $assessees[1]->place_of_birth,
                'gender' => $assessees[1]->gender,
                'nationality' => $assessees[1]->nationality ?? 'Indonesia',
                'email' => $assessees[1]->email,
                'mobile' => $assessees[1]->mobile,
                'phone' => $assessees[1]->phone,
                'address' => $assessees[1]->address,
                'city' => $assessees[1]->city,
                'province' => $assessees[1]->province,
                'postal_code' => $assessees[1]->postal_code,
                'current_company' => $assessees[1]->current_company,
                'current_position' => $assessees[1]->current_position,
                'current_industry' => $assessees[1]->current_industry,
                'certification_purpose' => 'Mendapatkan sertifikasi kompetensi untuk pengembangan karir',
                'target_competency' => 'Manajemen publikasi ilmiah elektronik',
                'status' => 'under_review',
                'declaration_agreed' => true,
                'declaration_signed_at' => now()->subDays(5),
                'submitted_at' => now()->subDays(5),
                'submitted_by' => 1,
                'current_review_level' => 1,
                'current_reviewer_id' => $users[0]->id,
                'is_active' => true,
                'created_by' => 1,
            ]);

            // Create review
            Apl01Review::create([
                'apl01_form_id' => $form2->id,
                'review_level' => 1,
                'review_level_name' => 'Initial Review',
                'reviewer_id' => $users[0]->id,
                'reviewer_role' => 'Reviewer',
                'decision' => 'pending',
                'assigned_at' => now()->subDays(5),
                'deadline' => now()->addDays(2),
                'is_current' => true,
                'is_final' => false,
                'created_by' => 1,
            ]);

            $forms[] = $form2;
        }

        // Form 3: Approved
        if ($assessees->count() > 2) {
            $form3 = Apl01Form::create([
                'assessee_id' => $assessees[2]->id,
                'scheme_id' => $scheme->id,
                'event_id' => $event?->id,
                'form_number' => 'APL-01-2025-0003',
                'submission_date' => now()->subDays(15),
                'full_name' => $assessees[2]->full_name,
                'id_number' => $assessees[2]->id_number,
                'date_of_birth' => $assessees[2]->date_of_birth,
                'place_of_birth' => $assessees[2]->place_of_birth,
                'gender' => $assessees[2]->gender,
                'nationality' => $assessees[2]->nationality ?? 'Indonesia',
                'email' => $assessees[2]->email,
                'mobile' => $assessees[2]->mobile,
                'phone' => $assessees[2]->phone,
                'address' => $assessees[2]->address,
                'city' => $assessees[2]->city,
                'province' => $assessees[2]->province,
                'postal_code' => $assessees[2]->postal_code,
                'current_company' => $assessees[2]->current_company,
                'current_position' => $assessees[2]->current_position,
                'current_industry' => $assessees[2]->current_industry,
                'certification_purpose' => 'Memenuhi persyaratan kompetensi jabatan',
                'target_competency' => 'Digitalisasi koleksi perpustakaan',
                'status' => 'approved',
                'declaration_agreed' => true,
                'declaration_signed_at' => now()->subDays(15),
                'submitted_at' => now()->subDays(15),
                'submitted_by' => 1,
                'completed_at' => now()->subDays(7),
                'completed_by' => $users[0]->id,
                'current_review_level' => 2,
                'is_active' => true,
                'created_by' => 1,
            ]);

            // Create review level 1 - Approved
            Apl01Review::create([
                'apl01_form_id' => $form3->id,
                'review_level' => 1,
                'review_level_name' => 'Initial Review',
                'reviewer_id' => $users[0]->id,
                'reviewer_role' => 'Reviewer',
                'decision' => 'approved',
                'review_notes' => 'Dokumen lengkap dan memenuhi persyaratan awal. Disetujui untuk lanjut ke tahap berikutnya.',
                'score' => 85,
                'assigned_at' => now()->subDays(15),
                'started_at' => now()->subDays(14),
                'completed_at' => now()->subDays(10),
                'review_duration' => 240,
                'is_current' => false,
                'is_final' => false,
                'created_by' => 1,
            ]);

            // Create review level 2 - Approved (Final)
            Apl01Review::create([
                'apl01_form_id' => $form3->id,
                'review_level' => 2,
                'review_level_name' => 'Final Approval',
                'reviewer_id' => $users[1]->id ?? $users[0]->id,
                'reviewer_role' => 'Senior Reviewer',
                'decision' => 'approved',
                'review_notes' => 'Semua persyaratan terpenuhi. Kandidat layak untuk mengikuti asesmen kompetensi.',
                'score' => 90,
                'assigned_at' => now()->subDays(10),
                'started_at' => now()->subDays(9),
                'completed_at' => now()->subDays(7),
                'review_duration' => 180,
                'is_current' => false,
                'is_final' => true,
                'created_by' => 1,
            ]);

            $forms[] = $form3;
        }

        // Form 4: Rejected
        if ($assessees->count() > 3) {
            $form4 = Apl01Form::create([
                'assessee_id' => $assessees[3]->id,
                'scheme_id' => $scheme->id,
                'event_id' => null,
                'form_number' => 'APL-01-2025-0004',
                'submission_date' => now()->subDays(10),
                'full_name' => $assessees[3]->full_name,
                'id_number' => $assessees[3]->id_number,
                'date_of_birth' => $assessees[3]->date_of_birth,
                'place_of_birth' => $assessees[3]->place_of_birth,
                'gender' => $assessees[3]->gender,
                'nationality' => $assessees[3]->nationality ?? 'Indonesia',
                'email' => $assessees[3]->email,
                'mobile' => $assessees[3]->mobile,
                'phone' => $assessees[3]->phone,
                'address' => $assessees[3]->address,
                'city' => $assessees[3]->city,
                'province' => $assessees[3]->province,
                'postal_code' => $assessees[3]->postal_code,
                'current_company' => $assessees[3]->current_company,
                'current_position' => $assessees[3]->current_position,
                'current_industry' => $assessees[3]->current_industry,
                'certification_purpose' => 'Pengembangan kompetensi profesional',
                'target_competency' => 'Pengelolaan repository institusi',
                'status' => 'rejected',
                'rejection_reason' => 'Dokumen pendukung tidak lengkap. Harap melengkapi CV dan sertifikat pelatihan.',
                'declaration_agreed' => true,
                'declaration_signed_at' => now()->subDays(10),
                'submitted_at' => now()->subDays(10),
                'submitted_by' => 1,
                'current_review_level' => 1,
                'is_active' => true,
                'created_by' => 1,
            ]);

            // Create review - Rejected
            Apl01Review::create([
                'apl01_form_id' => $form4->id,
                'review_level' => 1,
                'review_level_name' => 'Initial Review',
                'reviewer_id' => $users[0]->id,
                'reviewer_role' => 'Reviewer',
                'decision' => 'rejected',
                'review_notes' => 'Dokumen pendukung tidak lengkap. CV tidak dilampirkan dan sertifikat pelatihan yang relevan masih kurang. Silakan lengkapi dan submit ulang.',
                'score' => 45,
                'assigned_at' => now()->subDays(10),
                'started_at' => now()->subDays(9),
                'completed_at' => now()->subDays(8),
                'review_duration' => 120,
                'is_current' => true,
                'is_final' => true,
                'created_by' => 1,
            ]);

            $forms[] = $form4;
        }

        // Form 5: Escalated
        if ($assessees->count() > 4 && $users->count() > 2) {
            $form5 = Apl01Form::create([
                'assessee_id' => $assessees[4]->id,
                'scheme_id' => $scheme->id,
                'event_id' => $event?->id,
                'form_number' => 'APL-01-2025-0005',
                'submission_date' => now()->subDays(8),
                'full_name' => $assessees[4]->full_name,
                'id_number' => $assessees[4]->id_number,
                'date_of_birth' => $assessees[4]->date_of_birth,
                'place_of_birth' => $assessees[4]->place_of_birth,
                'gender' => $assessees[4]->gender,
                'nationality' => $assessees[4]->nationality ?? 'Indonesia',
                'email' => $assessees[4]->email,
                'mobile' => $assessees[4]->mobile,
                'phone' => $assessees[4]->phone,
                'address' => $assessees[4]->address,
                'city' => $assessees[4]->city,
                'province' => $assessees[4]->province,
                'postal_code' => $assessees[4]->postal_code,
                'current_company' => $assessees[4]->current_company,
                'current_position' => $assessees[4]->current_position,
                'current_industry' => $assessees[4]->current_industry,
                'certification_purpose' => 'Persiapan promosi jabatan',
                'target_competency' => 'Sistem informasi perpustakaan terintegrasi',
                'status' => 'under_review',
                'declaration_agreed' => true,
                'declaration_signed_at' => now()->subDays(8),
                'submitted_at' => now()->subDays(8),
                'submitted_by' => 1,
                'current_review_level' => 1,
                'current_reviewer_id' => $users[2]->id,
                'is_active' => true,
                'created_by' => 1,
            ]);

            // Create review - Escalated
            Apl01Review::create([
                'apl01_form_id' => $form5->id,
                'review_level' => 1,
                'review_level_name' => 'Initial Review',
                'reviewer_id' => $users[2]->id,
                'reviewer_role' => 'Reviewer',
                'decision' => 'pending',
                'review_notes' => 'Kasus khusus yang memerlukan persetujuan senior reviewer.',
                'assigned_at' => now()->subDays(8),
                'started_at' => now()->subDays(7),
                'deadline' => now()->addDays(1),
                'is_escalated' => true,
                'escalation_reason' => 'Terdapat konflik kepentingan, reviewer memiliki hubungan keluarga dengan kandidat.',
                'escalated_at' => now()->subDays(6),
                'escalated_to' => $users[1]->id ?? $users[0]->id,
                'is_current' => true,
                'is_final' => false,
                'created_by' => 1,
            ]);

            $forms[] = $form5;
        }

        $this->command->info('Created ' . count($forms) . ' sample APL-01 forms with reviews!');
    }
}
