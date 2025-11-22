<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\SchemeVersion;
use App\Models\SchemeUnit;
use App\Models\SchemeElement;
use App\Models\SchemeCriterion;
use App\Models\SchemeRequirement;
use App\Models\MasterStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user as creator
        $user = User::first();

        // Get or create statuses for schemes
        $activeStatus = MasterStatus::firstOrCreate(
            ['category' => 'scheme', 'code' => 'active'],
            ['label' => 'Active', 'description' => 'Scheme is active and ready for use', 'sort_order' => 1]
        );

        $draftStatus = MasterStatus::firstOrCreate(
            ['category' => 'scheme', 'code' => 'draft'],
            ['label' => 'Draft', 'description' => 'Scheme is in draft status', 'sort_order' => 2]
        );

        // Scheme 1: Pengelolaan Jurnal Elektronik
        $scheme1 = Scheme::create([
            'code' => 'SKM-001',
            'name' => 'Pengelolaan Jurnal Elektronik',
            'occupation_title' => 'Manajer Jurnal Elektronik',
            'qualification_level' => 'VI',
            'description' => 'Skema sertifikasi untuk kompetensi dalam mengelola jurnal elektronik ilmiah, termasuk proses editorial, peer review, dan publikasi artikel ilmiah.',
            'scheme_type' => 'occupation',
            'sector' => 'Information Technology',
            'status_id' => $activeStatus->id,
            'is_active' => true,
            'effective_date' => now()->subMonths(6),
            'review_date' => now()->addYears(3),
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        // Version 1.0 for Scheme 1
        $version1 = SchemeVersion::create([
            'scheme_id' => $scheme1->id,
            'version' => '1.0',
            'changes_summary' => 'Initial release of the certification scheme',
            'status' => 'active',
            'is_current' => true,
            'effective_date' => now()->subMonths(6),
            'approved_by' => $user->id ?? null,
            'approved_at' => now()->subMonths(6),
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        // Unit 1: Mengelola Sistem Jurnal Elektronik
        $unit1 = SchemeUnit::create([
            'scheme_version_id' => $version1->id,
            'code' => 'J.62.001.01',
            'title' => 'Mengelola Sistem Jurnal Elektronik',
            'description' => 'Unit kompetensi ini mencakup kemampuan untuk mengelola dan mengoperasikan sistem jurnal elektronik',
            'unit_type' => 'core',
            'credit_hours' => 40,
            'order' => 1,
            'is_mandatory' => true,
        ]);

        // Element 1.1: Mengonfigurasi Platform Jurnal
        $element1_1 = SchemeElement::create([
            'scheme_unit_id' => $unit1->id,
            'code' => '1',
            'title' => 'Mengonfigurasi Platform Jurnal',
            'description' => 'Kemampuan untuk mengonfigurasi dan menyesuaikan platform jurnal elektronik',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element1_1->id,
            'code' => '1.1',
            'description' => 'Platform jurnal elektronik dapat diinstal dan dikonfigurasi sesuai kebutuhan',
            'evidence_guide' => 'Bukti instalasi dan konfigurasi platform',
            'assessment_method' => 'practical',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element1_1->id,
            'code' => '1.2',
            'description' => 'Template dan tema jurnal dapat disesuaikan dengan identitas institusi',
            'evidence_guide' => 'Bukti customization template',
            'assessment_method' => 'portfolio',
            'order' => 2,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element1_1->id,
            'code' => '1.3',
            'description' => 'Metadata jurnal dapat dikelola dengan benar sesuai standar internasional',
            'evidence_guide' => 'Dokumentasi metadata management',
            'assessment_method' => 'practical',
            'order' => 3,
        ]);

        // Element 1.2: Mengelola Proses Editorial
        $element1_2 = SchemeElement::create([
            'scheme_unit_id' => $unit1->id,
            'code' => '2',
            'title' => 'Mengelola Proses Editorial',
            'description' => 'Kemampuan untuk mengelola workflow editorial dan peer review',
            'order' => 2,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element1_2->id,
            'code' => '2.1',
            'description' => 'Alur kerja editorial dapat dikonfigurasi sesuai kebijakan jurnal',
            'evidence_guide' => 'Dokumentasi workflow configuration',
            'assessment_method' => 'practical',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element1_2->id,
            'code' => '2.2',
            'description' => 'Proses peer review dapat dikelola dengan efektif',
            'evidence_guide' => 'Laporan proses peer review',
            'assessment_method' => 'portfolio',
            'order' => 2,
        ]);

        // Unit 2: Menerapkan Standar Publikasi Ilmiah
        $unit2 = SchemeUnit::create([
            'scheme_version_id' => $version1->id,
            'code' => 'J.62.001.02',
            'title' => 'Menerapkan Standar Publikasi Ilmiah',
            'description' => 'Unit kompetensi untuk menerapkan standar dan etika publikasi ilmiah',
            'unit_type' => 'core',
            'credit_hours' => 32,
            'order' => 2,
            'is_mandatory' => true,
        ]);

        // Element 2.1: Menerapkan Etika Publikasi
        $element2_1 = SchemeElement::create([
            'scheme_unit_id' => $unit2->id,
            'code' => '1',
            'title' => 'Menerapkan Etika Publikasi',
            'description' => 'Kemampuan untuk menerapkan etika publikasi dan mencegah plagiarisme',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element2_1->id,
            'code' => '1.1',
            'description' => 'Kebijakan etika publikasi dapat diterapkan dengan konsisten',
            'evidence_guide' => 'Dokumentasi implementasi kebijakan etika',
            'assessment_method' => 'written',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element2_1->id,
            'code' => '1.2',
            'description' => 'Pengecekan plagiarisme dapat dilakukan menggunakan tools yang tepat',
            'evidence_guide' => 'Laporan hasil pengecekan plagiarisme',
            'assessment_method' => 'practical',
            'order' => 2,
        ]);

        // Requirements for Version 1
        SchemeRequirement::create([
            'scheme_version_id' => $version1->id,
            'requirement_type' => 'education',
            'title' => 'Pendidikan Minimal S1',
            'description' => 'Memiliki ijazah minimal Sarjana (S1) dari institusi pendidikan tinggi yang terakreditasi',
            'is_mandatory' => true,
            'order' => 1,
        ]);

        SchemeRequirement::create([
            'scheme_version_id' => $version1->id,
            'requirement_type' => 'experience',
            'title' => 'Pengalaman Mengelola Jurnal',
            'description' => 'Minimal 2 tahun pengalaman dalam mengelola jurnal elektronik atau publikasi ilmiah',
            'is_mandatory' => true,
            'order' => 2,
        ]);

        SchemeRequirement::create([
            'scheme_version_id' => $version1->id,
            'requirement_type' => 'training',
            'title' => 'Pelatihan Open Journal Systems (OJS)',
            'description' => 'Telah mengikuti pelatihan OJS atau platform jurnal elektronik sejenis',
            'is_mandatory' => false,
            'order' => 3,
        ]);

        // Scheme 2: Penerapan IT untuk Artikel Ilmiah
        $scheme2 = Scheme::create([
            'code' => 'SKM-002',
            'name' => 'Penerapan IT untuk Artikel Ilmiah',
            'occupation_title' => 'Spesialis Publikasi Digital',
            'qualification_level' => 'V',
            'description' => 'Skema sertifikasi untuk kompetensi dalam menerapkan teknologi informasi untuk penulisan, publikasi, dan diseminasi artikel ilmiah.',
            'scheme_type' => 'occupation',
            'sector' => 'Information Technology',
            'status_id' => $activeStatus->id,
            'is_active' => true,
            'effective_date' => now()->subMonths(3),
            'review_date' => now()->addYears(3),
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        // Version 1.0 for Scheme 2
        $version2 = SchemeVersion::create([
            'scheme_id' => $scheme2->id,
            'version' => '1.0',
            'changes_summary' => 'Initial certification scheme for IT application in scholarly articles',
            'status' => 'active',
            'is_current' => true,
            'effective_date' => now()->subMonths(3),
            'approved_by' => $user->id ?? null,
            'approved_at' => now()->subMonths(3),
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        // Unit 1: Menulis Artikel dengan Tools Digital
        $unit2_1 = SchemeUnit::create([
            'scheme_version_id' => $version2->id,
            'code' => 'J.62.002.01',
            'title' => 'Menulis Artikel dengan Tools Digital',
            'description' => 'Kemampuan menggunakan tools digital untuk penulisan artikel ilmiah',
            'unit_type' => 'core',
            'credit_hours' => 24,
            'order' => 1,
            'is_mandatory' => true,
        ]);

        $element2_1_1 = SchemeElement::create([
            'scheme_unit_id' => $unit2_1->id,
            'code' => '1',
            'title' => 'Menggunakan Reference Management Tools',
            'description' => 'Kemampuan menggunakan tools manajemen referensi seperti Mendeley, Zotero, atau EndNote',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element2_1_1->id,
            'code' => '1.1',
            'description' => 'Reference management software dapat digunakan untuk mengelola sitasi',
            'evidence_guide' => 'Demonstrasi penggunaan reference management tools',
            'assessment_method' => 'practical',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element2_1_1->id,
            'code' => '1.2',
            'description' => 'Citation style dapat diterapkan sesuai dengan format jurnal target',
            'evidence_guide' => 'Contoh artikel dengan citation yang benar',
            'assessment_method' => 'portfolio',
            'order' => 2,
        ]);

        // Unit 2: Mengelola Data Penelitian
        $unit2_2 = SchemeUnit::create([
            'scheme_version_id' => $version2->id,
            'code' => 'J.62.002.02',
            'title' => 'Mengelola Data Penelitian',
            'description' => 'Kemampuan mengelola dan menyimpan data penelitian dengan baik',
            'unit_type' => 'core',
            'credit_hours' => 20,
            'order' => 2,
            'is_mandatory' => true,
        ]);

        $element2_2_1 = SchemeElement::create([
            'scheme_unit_id' => $unit2_2->id,
            'code' => '1',
            'title' => 'Menyimpan Data dengan Aman',
            'description' => 'Kemampuan menyimpan data penelitian dengan sistem backup yang baik',
            'order' => 1,
        ]);

        SchemeCriterion::create([
            'scheme_element_id' => $element2_2_1->id,
            'code' => '1.1',
            'description' => 'Data penelitian dapat disimpan dengan sistem version control',
            'evidence_guide' => 'Dokumentasi penggunaan version control system',
            'assessment_method' => 'practical',
            'order' => 1,
        ]);

        // Requirements for Version 2
        SchemeRequirement::create([
            'scheme_version_id' => $version2->id,
            'requirement_type' => 'education',
            'title' => 'Pendidikan Minimal S1',
            'description' => 'Memiliki ijazah minimal Sarjana (S1) di bidang yang relevan',
            'is_mandatory' => true,
            'order' => 1,
        ]);

        SchemeRequirement::create([
            'scheme_version_id' => $version2->id,
            'requirement_type' => 'experience',
            'title' => 'Pengalaman Publikasi',
            'description' => 'Minimal telah mempublikasikan 3 artikel ilmiah di jurnal bereputasi',
            'is_mandatory' => true,
            'order' => 2,
        ]);

        // Scheme 3: Manajemen Perpustakaan Digital (Draft)
        $scheme3 = Scheme::create([
            'code' => 'SKM-003',
            'name' => 'Manajemen Perpustakaan Digital',
            'occupation_title' => 'Pustakawan Digital',
            'qualification_level' => 'V',
            'description' => 'Skema sertifikasi untuk kompetensi dalam mengelola perpustakaan digital dan repository institusi.',
            'scheme_type' => 'occupation',
            'sector' => 'Library Science',
            'status_id' => $draftStatus->id,
            'is_active' => false,
            'effective_date' => null,
            'review_date' => null,
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        // Version 1.0 (Draft) for Scheme 3
        $version3 = SchemeVersion::create([
            'scheme_id' => $scheme3->id,
            'version' => '1.0',
            'changes_summary' => 'Draft version - under development',
            'status' => 'draft',
            'is_current' => true,
            'effective_date' => null,
            'approved_by' => null,
            'approved_at' => null,
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
        ]);

        $this->command->info('✓ Created 3 certification schemes');
        $this->command->info('  - SKM-001: Pengelolaan Jurnal Elektronik (Active)');
        $this->command->info('  - SKM-002: Penerapan IT untuk Artikel Ilmiah (Active)');
        $this->command->info('  - SKM-003: Manajemen Perpustakaan Digital (Draft)');
    }
}
