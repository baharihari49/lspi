<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LspProfile;
use App\Models\OrgSetting;
use App\Models\Tuk;
use App\Models\TukFacility;
use App\Models\TukSchedule;
use App\Models\MasterStatus;
use App\Models\User;
use Carbon\Carbon;

class LspTukSeeder extends Seeder
{
    public function run(): void
    {
        // Get active status
        $activeStatus = MasterStatus::where('code', 'active')->first();

        // Get first user for assignments
        $adminUser = User::first();

        // Create LSP Profile
        $lspProfile = LspProfile::create([
            'code' => 'LSP-001',
            'name' => 'LSP Pustaka Ilmiah Elektronik',
            'legal_name' => 'Lembaga Sertifikasi Profesi Pustaka Ilmiah Elektronik',
            'license_number' => 'KEP-123/BNSP/XII/2024',
            'license_issued_date' => Carbon::create(2024, 1, 15),
            'license_expiry_date' => Carbon::create(2027, 1, 14),
            'accreditation_number' => 'ACC-456/BNSP/2024',
            'accreditation_expiry_date' => Carbon::create(2027, 1, 14),
            'email' => 'info@lsp-pie.org',
            'phone' => '021-12345678',
            'fax' => '021-12345679',
            'website' => 'https://lsp-pie.org',
            'address' => 'Jl. Pendidikan No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
            'description' => 'LSP Pustaka Ilmiah Elektronik adalah lembaga sertifikasi profesi yang berfokus pada kompetensi pengelolaan pustaka elektronik dan literasi digital.',
            'vision' => 'Menjadi lembaga sertifikasi profesi terkemuka dalam bidang pengelolaan pustaka elektronik di Indonesia.',
            'mission' => 'Meningkatkan kompetensi profesional pustakawan dan pengelola informasi digital melalui sertifikasi yang kredibel dan terstandar.',
            'director_name' => 'Dr. Ahmad Pustaka, M.Si',
            'director_position' => 'Direktur Utama',
            'director_phone' => '0812-3456-7890',
            'director_email' => 'director@lsp-pie.org',
            'status_id' => $activeStatus?->id,
            'is_active' => true,
        ]);

        // Create Organization Settings
        $settings = [
            ['key' => 'assessment_duration', 'value' => '180', 'type' => 'number', 'group' => 'assessment', 'description' => 'Duration of assessment in minutes', 'is_public' => false, 'is_editable' => true],
            ['key' => 'max_retake_attempts', 'value' => '3', 'type' => 'number', 'group' => 'assessment', 'description' => 'Maximum number of retake attempts', 'is_public' => false, 'is_editable' => true],
            ['key' => 'passing_score', 'value' => '70', 'type' => 'number', 'group' => 'assessment', 'description' => 'Minimum passing score percentage', 'is_public' => false, 'is_editable' => true],
            ['key' => 'certificate_validity', 'value' => '36', 'type' => 'number', 'group' => 'certificate', 'description' => 'Certificate validity in months', 'is_public' => true, 'is_editable' => true],
            ['key' => 'registration_fee', 'value' => '500000', 'type' => 'number', 'group' => 'payment', 'description' => 'Registration fee in IDR', 'is_public' => true, 'is_editable' => true],
            ['key' => 'assessment_fee', 'value' => '1500000', 'type' => 'number', 'group' => 'payment', 'description' => 'Assessment fee in IDR', 'is_public' => true, 'is_editable' => true],
            ['key' => 'enable_online_assessment', 'value' => 'true', 'type' => 'boolean', 'group' => 'assessment', 'description' => 'Enable online assessment', 'is_public' => true, 'is_editable' => true],
        ];

        foreach ($settings as $setting) {
            OrgSetting::create($setting);
        }

        // Create TUKs
        $tuk1 = Tuk::create([
            'code' => 'TUK-001',
            'name' => 'TUK Pusat Jakarta',
            'type' => 'permanent',
            'description' => 'Tempat Uji Kompetensi utama berlokasi di Jakarta Pusat dengan fasilitas lengkap dan modern.',
            'address' => 'Jl. Gatot Subroto No. 45',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10270',
            'latitude' => -6.208763,
            'longitude' => 106.845599,
            'phone' => '021-98765432',
            'email' => 'tuk.jakarta@lsp-pie.org',
            'pic_name' => 'Budi Santoso',
            'pic_phone' => '0813-9876-5432',
            'capacity' => 50,
            'room_count' => 5,
            'area_size' => 350.00,
            'status_id' => $activeStatus?->id,
            'is_active' => true,
            'manager_id' => $adminUser?->id,
            'created_by' => $adminUser?->id,
        ]);

        $tuk2 = Tuk::create([
            'code' => 'TUK-002',
            'name' => 'TUK Bandung',
            'type' => 'permanent',
            'description' => 'TUK regional Bandung melayani wilayah Jawa Barat.',
            'address' => 'Jl. Asia Afrika No. 123',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
            'latitude' => -6.921891,
            'longitude' => 107.607544,
            'phone' => '022-12345678',
            'email' => 'tuk.bandung@lsp-pie.org',
            'pic_name' => 'Siti Rahayu',
            'pic_phone' => '0822-1234-5678',
            'capacity' => 30,
            'room_count' => 3,
            'area_size' => 200.00,
            'status_id' => $activeStatus?->id,
            'is_active' => true,
            'manager_id' => $adminUser?->id,
            'created_by' => $adminUser?->id,
        ]);

        $tuk3 = Tuk::create([
            'code' => 'TUK-003',
            'name' => 'TUK Mobile Jawa Timur',
            'type' => 'mobile',
            'description' => 'TUK bergerak yang melayani berbagai lokasi di Jawa Timur.',
            'address' => 'Jl. Tunjungan No. 88',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60275',
            'latitude' => -7.250445,
            'longitude' => 112.768845,
            'phone' => '031-87654321',
            'email' => 'tuk.jatim@lsp-pie.org',
            'pic_name' => 'Agus Wijaya',
            'pic_phone' => '0856-7890-1234',
            'capacity' => 20,
            'room_count' => 2,
            'area_size' => 150.00,
            'status_id' => $activeStatus?->id,
            'is_active' => true,
            'manager_id' => $adminUser?->id,
            'created_by' => $adminUser?->id,
        ]);

        // Create TUK Facilities
        $facilities = [
            // TUK Jakarta
            ['tuk_id' => $tuk1->id, 'name' => 'Komputer Desktop', 'category' => 'technology', 'description' => 'Komputer untuk ujian berbasis komputer', 'quantity' => 50, 'condition' => 'excellent'],
            ['tuk_id' => $tuk1->id, 'name' => 'Meja Ujian', 'category' => 'furniture', 'description' => 'Meja untuk peserta ujian', 'quantity' => 50, 'condition' => 'good'],
            ['tuk_id' => $tuk1->id, 'name' => 'AC Split', 'category' => 'equipment', 'description' => 'Pendingin ruangan', 'quantity' => 5, 'condition' => 'good', 'last_maintenance' => Carbon::now()->subMonths(2), 'next_maintenance' => Carbon::now()->addMonths(4)],
            ['tuk_id' => $tuk1->id, 'name' => 'Fire Extinguisher', 'category' => 'safety', 'description' => 'Alat pemadam api', 'quantity' => 8, 'condition' => 'excellent', 'last_maintenance' => Carbon::now()->subMonths(1), 'next_maintenance' => Carbon::now()->addMonths(11)],

            // TUK Bandung
            ['tuk_id' => $tuk2->id, 'name' => 'Komputer Desktop', 'category' => 'technology', 'description' => 'Komputer untuk ujian berbasis komputer', 'quantity' => 30, 'condition' => 'good'],
            ['tuk_id' => $tuk2->id, 'name' => 'Proyektor', 'category' => 'technology', 'description' => 'Proyektor untuk presentasi', 'quantity' => 3, 'condition' => 'good'],
            ['tuk_id' => $tuk2->id, 'name' => 'Whiteboard', 'category' => 'equipment', 'description' => 'Papan tulis', 'quantity' => 3, 'condition' => 'excellent'],

            // TUK Surabaya
            ['tuk_id' => $tuk3->id, 'name' => 'Laptop', 'category' => 'technology', 'description' => 'Laptop portable untuk TUK mobile', 'quantity' => 20, 'condition' => 'good'],
            ['tuk_id' => $tuk3->id, 'name' => 'Portable Table', 'category' => 'furniture', 'description' => 'Meja lipat portable', 'quantity' => 20, 'condition' => 'good'],
        ];

        foreach ($facilities as $facility) {
            TukFacility::create($facility);
        }

        // Create TUK Schedules
        $schedules = [
            // TUK Jakarta - Next 2 weeks
            ['tuk_id' => $tuk1->id, 'date' => Carbon::now()->addDays(3), 'start_time' => '08:00', 'end_time' => '12:00', 'status' => 'available', 'available_capacity' => 50, 'created_by' => $adminUser?->id],
            ['tuk_id' => $tuk1->id, 'date' => Carbon::now()->addDays(3), 'start_time' => '13:00', 'end_time' => '17:00', 'status' => 'available', 'available_capacity' => 50, 'created_by' => $adminUser?->id],
            ['tuk_id' => $tuk1->id, 'date' => Carbon::now()->addDays(7), 'start_time' => '08:00', 'end_time' => '12:00', 'status' => 'booked', 'available_capacity' => 0, 'notes' => 'Ujian Batch 01/2025', 'created_by' => $adminUser?->id],
            ['tuk_id' => $tuk1->id, 'date' => Carbon::now()->addDays(10), 'start_time' => '08:00', 'end_time' => '16:00', 'status' => 'maintenance', 'available_capacity' => 0, 'notes' => 'Perawatan rutin AC dan komputer', 'created_by' => $adminUser?->id],

            // TUK Bandung
            ['tuk_id' => $tuk2->id, 'date' => Carbon::now()->addDays(5), 'start_time' => '08:00', 'end_time' => '12:00', 'status' => 'available', 'available_capacity' => 30, 'created_by' => $adminUser?->id],
            ['tuk_id' => $tuk2->id, 'date' => Carbon::now()->addDays(5), 'start_time' => '13:00', 'end_time' => '17:00', 'status' => 'available', 'available_capacity' => 30, 'created_by' => $adminUser?->id],

            // TUK Surabaya
            ['tuk_id' => $tuk3->id, 'date' => Carbon::now()->addDays(14), 'start_time' => '09:00', 'end_time' => '13:00', 'status' => 'available', 'available_capacity' => 20, 'created_by' => $adminUser?->id],
        ];

        foreach ($schedules as $schedule) {
            TukSchedule::create($schedule);
        }

        $this->command->info('LSP Profile, Organization Settings, TUK, Facilities, and Schedules seeded successfully!');
    }
}
