<?php

namespace Database\Seeders;

use App\Models\Apl01FormField;
use App\Models\Scheme;
use Illuminate\Database\Seeder;

class Apl01FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first scheme
        $scheme = Scheme::first();

        if (!$scheme) {
            $this->command->warn('No schemes found. Please run SchemeSeeder first.');
            return;
        }

        $this->command->info('Creating APL-01 form fields for scheme: ' . $scheme->name);

        $fields = [
            // Section: Self Assessment
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'section_self_assessment',
                'field_label' => 'Bagian A: Data Pribadi dan Asesmen Mandiri',
                'field_description' => null,
                'field_type' => 'section_header',
                'section' => 'A',
                'order' => 1,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'understand_competency_standard',
                'field_label' => 'Apakah Anda memahami standar kompetensi yang akan diujikan?',
                'field_description' => 'Silakan baca dengan seksama standar kompetensi sebelum melanjutkan',
                'field_type' => 'yesno',
                'field_options' => null,
                'validation_rules' => ['required'],
                'section' => 'A',
                'order' => 2,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'help_text' => 'Pastikan Anda telah membaca dan memahami standar kompetensi',
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'competency_confidence_level',
                'field_label' => 'Seberapa yakin Anda dengan kompetensi yang Anda miliki?',
                'field_description' => 'Nilai 1 = Tidak yakin sama sekali, 5 = Sangat yakin',
                'field_type' => 'rating',
                'field_options' => null,
                'validation_rules' => ['required', 'integer', 'min:1', 'max:5'],
                'section' => 'A',
                'order' => 3,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],

            // Section: Work Experience
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'section_work_experience',
                'field_label' => 'Bagian B: Riwayat Pekerjaan',
                'field_description' => null,
                'field_type' => 'section_header',
                'section' => 'B',
                'order' => 10,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'years_of_experience',
                'field_label' => 'Berapa tahun pengalaman kerja Anda di bidang ini?',
                'field_description' => null,
                'field_type' => 'number',
                'field_options' => null,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'section' => 'B',
                'order' => 11,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'placeholder' => 'Contoh: 5',
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'previous_projects',
                'field_label' => 'Jelaskan proyek atau pekerjaan relevan yang pernah Anda tangani',
                'field_description' => 'Sebutkan minimal 3 proyek yang paling relevan',
                'field_type' => 'textarea',
                'field_options' => null,
                'validation_rules' => ['required', 'min:100'],
                'section' => 'B',
                'order' => 12,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'placeholder' => 'Jelaskan proyek-proyek yang pernah Anda kerjakan...',
                'is_active' => true,
            ],

            // Section: Education
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'section_education',
                'field_label' => 'Bagian C: Riwayat Pendidikan dan Pelatihan',
                'field_description' => null,
                'field_type' => 'section_header',
                'section' => 'C',
                'order' => 20,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'highest_education',
                'field_label' => 'Pendidikan Terakhir',
                'field_description' => null,
                'field_type' => 'select',
                'field_options' => [
                    ['value' => 'sma', 'label' => 'SMA/SMK'],
                    ['value' => 'd3', 'label' => 'Diploma 3'],
                    ['value' => 's1', 'label' => 'Sarjana (S1)'],
                    ['value' => 's2', 'label' => 'Magister (S2)'],
                    ['value' => 's3', 'label' => 'Doktor (S3)'],
                ],
                'validation_rules' => ['required'],
                'section' => 'C',
                'order' => 21,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'relevant_trainings',
                'field_label' => 'Pelatihan yang Relevan',
                'field_description' => 'Sebutkan pelatihan/sertifikasi yang relevan dengan kompetensi yang diajukan',
                'field_type' => 'textarea',
                'field_options' => null,
                'validation_rules' => ['nullable'],
                'section' => 'C',
                'order' => 22,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'placeholder' => 'Contoh: Pelatihan Manajemen Perpustakaan Digital (2022)',
                'is_active' => true,
            ],

            // Section: Supporting Documents
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'section_documents',
                'field_label' => 'Bagian D: Dokumen Pendukung',
                'field_description' => null,
                'field_type' => 'section_header',
                'section' => 'D',
                'order' => 30,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'cv_file',
                'field_label' => 'Upload CV/Resume',
                'field_description' => 'Format: PDF, maksimal 2MB',
                'field_type' => 'file',
                'field_options' => null,
                'validation_rules' => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'file_config' => [
                    'max_size' => 2048,
                    'allowed_types' => ['pdf'],
                ],
                'section' => 'D',
                'order' => 31,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'help_text' => 'Upload CV dalam format PDF, maksimal 2MB',
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'certificate_files',
                'field_label' => 'Upload Sertifikat Pelatihan/Pendidikan',
                'field_description' => 'Upload sertifikat yang relevan (dapat lebih dari 1 file)',
                'field_type' => 'file',
                'field_options' => null,
                'validation_rules' => ['nullable'],
                'file_config' => [
                    'max_size' => 5120,
                    'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'multiple' => true,
                ],
                'section' => 'D',
                'order' => 32,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'help_text' => 'Format: PDF, JPG, PNG. Maksimal 5MB per file',
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'portfolio_files',
                'field_label' => 'Upload Portofolio',
                'field_description' => 'Upload dokumen portofolio pekerjaan Anda',
                'field_type' => 'file',
                'field_options' => null,
                'validation_rules' => ['nullable'],
                'file_config' => [
                    'max_size' => 10240,
                    'allowed_types' => ['pdf', 'doc', 'docx'],
                    'multiple' => true,
                ],
                'section' => 'D',
                'order' => 33,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'help_text' => 'Format: PDF, DOC, DOCX. Maksimal 10MB per file',
                'is_active' => true,
            ],

            // Section: Additional Information
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'section_additional',
                'field_label' => 'Bagian E: Informasi Tambahan',
                'field_description' => null,
                'field_type' => 'section_header',
                'section' => 'E',
                'order' => 40,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'special_needs',
                'field_label' => 'Apakah Anda memiliki kebutuhan khusus untuk pelaksanaan asesmen?',
                'field_description' => null,
                'field_type' => 'yesno',
                'field_options' => null,
                'validation_rules' => ['required'],
                'section' => 'E',
                'order' => 41,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'special_needs_description',
                'field_label' => 'Jelaskan kebutuhan khusus Anda',
                'field_description' => 'Misal: kursi roda, alat bantu dengar, dll',
                'field_type' => 'textarea',
                'field_options' => null,
                'validation_rules' => ['nullable'],
                'conditional_logic' => [
                    'field' => 'special_needs',
                    'operator' => 'equals',
                    'value' => 'yes',
                ],
                'section' => 'E',
                'order' => 42,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'placeholder' => 'Jelaskan kebutuhan khusus Anda...',
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'preferred_assessment_method',
                'field_label' => 'Metode Asesmen yang Diinginkan',
                'field_description' => 'Pilih metode asesmen yang sesuai dengan Anda',
                'field_type' => 'radio',
                'field_options' => [
                    ['value' => 'observasi', 'label' => 'Observasi Langsung'],
                    ['value' => 'portofolio', 'label' => 'Penilaian Portofolio'],
                    ['value' => 'wawancara', 'label' => 'Wawancara'],
                    ['value' => 'kombinasi', 'label' => 'Kombinasi'],
                ],
                'validation_rules' => ['required'],
                'section' => 'E',
                'order' => 43,
                'is_required' => true,
                'is_enabled' => true,
                'is_visible' => true,
                'is_active' => true,
            ],
            [
                'scheme_id' => $scheme->id,
                'field_name' => 'additional_notes',
                'field_label' => 'Catatan Tambahan',
                'field_description' => 'Informasi lain yang ingin Anda sampaikan',
                'field_type' => 'textarea',
                'field_options' => null,
                'validation_rules' => ['nullable'],
                'section' => 'E',
                'order' => 44,
                'is_required' => false,
                'is_enabled' => true,
                'is_visible' => true,
                'placeholder' => 'Tuliskan informasi tambahan jika ada...',
                'is_active' => true,
            ],
        ];

        foreach ($fields as $fieldData) {
            Apl01FormField::create([
                ...$fieldData,
                'created_by' => 1,
            ]);
        }

        $this->command->info('Created ' . count($fields) . ' form fields successfully!');
    }
}
