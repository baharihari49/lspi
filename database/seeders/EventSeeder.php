<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventTuk;
use App\Models\EventAssessor;
use App\Models\EventMaterial;
use App\Models\EventAttendance;
use App\Models\Scheme;
use App\Models\MasterStatus;
use App\Models\User;
use App\Models\Tuk;
use App\Models\Assessor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // Get or create event statuses
        $plannedStatus = MasterStatus::firstOrCreate(
            ['category' => 'event', 'code' => 'planned'],
            ['label' => 'Planned', 'description' => 'Event is being planned', 'sort_order' => 1]
        );

        $openStatus = MasterStatus::firstOrCreate(
            ['category' => 'event', 'code' => 'open'],
            ['label' => 'Open for Registration', 'description' => 'Registration is open', 'sort_order' => 2]
        );

        $ongoingStatus = MasterStatus::firstOrCreate(
            ['category' => 'event', 'code' => 'ongoing'],
            ['label' => 'Ongoing', 'description' => 'Event is in progress', 'sort_order' => 3]
        );

        $scheme = Scheme::first();
        $users = User::where('is_active', true)->get();
        $tuks = Tuk::where('is_active', true)->get();
        $assessors = Assessor::where('is_active', true)->get();

        // Event 1: Upcoming Certification Event
        $event1 = Event::create([
            'code' => 'EVT-2025-001',
            'name' => 'Sertifikasi Pengelolaan Jurnal Elektronik - Batch 1',
            'scheme_id' => $scheme?->id,
            'description' => 'Event sertifikasi kompetensi untuk pengelola jurnal elektronik ilmiah',
            'event_type' => 'certification',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(32),
            'registration_start' => now(),
            'registration_end' => now()->addDays(25),
            'max_participants' => 30,
            'current_participants' => 0,
            'registration_fee' => 2500000,
            'status_id' => $openStatus->id,
            'is_published' => true,
            'is_active' => true,
            'location' => 'Jakarta',
            'location_address' => 'Gedung LSP-PIE, Jl. Sudirman No. 123, Jakarta Pusat',
            'created_by' => $user?->id,
            'updated_by' => $user?->id,
        ]);

        // Event 1 Sessions
        $session1_1 = EventSession::create([
            'event_id' => $event1->id,
            'session_code' => 'S1',
            'name' => 'Registrasi dan Pembukaan',
            'session_date' => now()->addDays(30),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'room' => 'Ruang Utama',
            'description' => 'Registrasi peserta dan acara pembukaan',
            'max_participants' => 30,
            'status' => 'scheduled',
            'order' => 1,
        ]);

        $session1_2 = EventSession::create([
            'event_id' => $event1->id,
            'session_code' => 'S2',
            'name' => 'Materi Teori Pengelolaan Jurnal',
            'session_date' => now()->addDays(30),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'room' => 'Ruang Seminar A',
            'description' => 'Pemaparan teori dan konsep pengelolaan jurnal elektronik',
            'max_participants' => 30,
            'status' => 'scheduled',
            'order' => 2,
        ]);

        $session1_3 = EventSession::create([
            'event_id' => $event1->id,
            'session_code' => 'S3',
            'name' => 'Praktik OJS',
            'session_date' => now()->addDays(31),
            'start_time' => '09:00',
            'end_time' => '15:00',
            'room' => 'Lab Komputer',
            'description' => 'Praktik langsung menggunakan Open Journal Systems',
            'max_participants' => 30,
            'status' => 'scheduled',
            'order' => 3,
        ]);

        $session1_4 = EventSession::create([
            'event_id' => $event1->id,
            'session_code' => 'S4',
            'name' => 'Ujian Kompetensi',
            'session_date' => now()->addDays(32),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'room' => 'Ruang Ujian',
            'description' => 'Ujian praktik dan teori kompetensi',
            'max_participants' => 30,
            'status' => 'scheduled',
            'order' => 4,
        ]);

        // Event 1 TUK Assignment
        if ($tuks->isNotEmpty()) {
            EventTuk::create([
                'event_id' => $event1->id,
                'tuk_id' => $tuks->first()->id,
                'status' => 'confirmed',
                'notes' => 'TUK utama untuk event sertifikasi',
                'confirmed_at' => now(),
                'confirmed_by' => $user?->id,
            ]);
        }

        // Event 1 Assessors
        if ($assessors->count() >= 2) {
            EventAssessor::create([
                'event_id' => $event1->id,
                'assessor_id' => $assessors->first()->id,
                'event_session_id' => $session1_4->id,
                'role' => 'lead',
                'status' => 'confirmed',
                'honorarium_amount' => 3000000,
                'payment_status' => 'pending',
                'invited_at' => now()->subDays(5),
                'confirmed_at' => now()->subDays(3),
            ]);

            EventAssessor::create([
                'event_id' => $event1->id,
                'assessor_id' => $assessors->skip(1)->first()->id,
                'event_session_id' => $session1_4->id,
                'role' => 'member',
                'status' => 'invited',
                'honorarium_amount' => 2500000,
                'payment_status' => 'pending',
                'invited_at' => now()->subDays(5),
            ]);

            if ($assessors->count() >= 3) {
                EventAssessor::create([
                    'event_id' => $event1->id,
                    'assessor_id' => $assessors->skip(2)->first()->id,
                    'event_session_id' => $session1_4->id,
                    'role' => 'observer',
                    'status' => 'confirmed',
                    'honorarium_amount' => 1500000,
                    'payment_status' => 'pending',
                    'invited_at' => now()->subDays(5),
                    'confirmed_at' => now()->subDays(2),
                ]);
            }
        }

        // Event 1 Materials
        EventMaterial::create([
            'event_id' => $event1->id,
            'event_session_id' => $session1_2->id,
            'title' => 'Modul Pengelolaan Jurnal Elektronik',
            'description' => 'Materi lengkap tentang pengelolaan jurnal elektronik',
            'material_type' => 'document',
            'file_path' => 'event-materials/modul-jurnal.pdf',
            'file_name' => 'modul-jurnal.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 2048000,
            'is_public' => true,
            'is_downloadable' => true,
            'download_count' => 0,
            'order' => 1,
        ]);

        EventMaterial::create([
            'event_id' => $event1->id,
            'event_session_id' => $session1_2->id,
            'title' => 'Presentasi Teori Pengelolaan Jurnal',
            'description' => 'Slide presentasi untuk sesi teori',
            'material_type' => 'presentation',
            'file_path' => 'event-materials/presentasi-teori.pptx',
            'file_name' => 'presentasi-teori.pptx',
            'file_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'file_size' => 5120000,
            'is_public' => true,
            'is_downloadable' => true,
            'download_count' => 0,
            'order' => 2,
        ]);

        // Event 2: Ongoing Workshop
        $event2 = Event::create([
            'code' => 'EVT-2025-002',
            'name' => 'Workshop OJS (Open Journal Systems)',
            'scheme_id' => null,
            'description' => 'Workshop pelatihan penggunaan OJS untuk pengelola jurnal',
            'event_type' => 'workshop',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(2),
            'registration_start' => now()->subDays(30),
            'registration_end' => now()->subDays(5),
            'max_participants' => 25,
            'current_participants' => 18,
            'registration_fee' => 1500000,
            'status_id' => $ongoingStatus->id,
            'is_published' => true,
            'is_active' => true,
            'location' => 'Bandung',
            'location_address' => 'Hotel Grand Serela, Bandung',
            'created_by' => $user?->id,
            'updated_by' => $user?->id,
        ]);

        // Event 2 Sessions
        $session2_1 = EventSession::create([
            'event_id' => $event2->id,
            'session_code' => 'Morning',
            'name' => 'Pengenalan OJS',
            'session_date' => now()->subDays(1),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'room' => 'Ruang Meeting 1',
            'description' => 'Pengenalan fitur-fitur OJS',
            'max_participants' => 25,
            'status' => 'completed',
            'order' => 1,
        ]);

        $session2_2 = EventSession::create([
            'event_id' => $event2->id,
            'session_code' => 'Day2',
            'name' => 'Praktik Setup OJS',
            'session_date' => now(),
            'start_time' => '09:00',
            'end_time' => '16:00',
            'room' => 'Ruang Meeting 1',
            'description' => 'Hands-on setup dan konfigurasi OJS',
            'max_participants' => 25,
            'status' => 'ongoing',
            'order' => 2,
        ]);

        $session2_3 = EventSession::create([
            'event_id' => $event2->id,
            'session_code' => 'Day3',
            'name' => 'Manajemen Workflow OJS',
            'session_date' => now()->addDays(1),
            'start_time' => '09:00',
            'end_time' => '16:00',
            'room' => 'Ruang Meeting 1',
            'description' => 'Praktik mengelola workflow submission di OJS',
            'max_participants' => 25,
            'status' => 'scheduled',
            'order' => 3,
        ]);

        // Event 2 TUK Assignment
        if ($tuks->count() >= 2) {
            EventTuk::create([
                'event_id' => $event2->id,
                'tuk_id' => $tuks->skip(1)->first()->id,
                'status' => 'confirmed',
                'notes' => 'Hotel dengan fasilitas lab komputer',
                'confirmed_at' => now()->subDays(10),
                'confirmed_by' => $user?->id,
            ]);
        }

        // Event 2 Materials
        EventMaterial::create([
            'event_id' => $event2->id,
            'event_session_id' => $session2_1->id,
            'title' => 'Panduan OJS untuk Pengelola Jurnal',
            'description' => 'Handout lengkap penggunaan OJS',
            'material_type' => 'document',
            'file_path' => 'event-materials/panduan-ojs.pdf',
            'file_name' => 'panduan-ojs.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 3072000,
            'is_public' => true,
            'is_downloadable' => true,
            'download_count' => 15,
            'order' => 1,
        ]);

        // Event 2 Attendance (for ongoing event)
        if ($users->count() >= 5) {
            foreach ($users->take(5) as $index => $participant) {
                // Day 1 session (completed, all checked out)
                EventAttendance::create([
                    'event_id' => $event2->id,
                    'user_id' => $participant->id,
                    'event_session_id' => $session2_1->id,
                    'check_in_at' => now()->subDays(1)->setTime(8, 30 + $index * 2),
                    'check_out_at' => now()->subDays(1)->setTime(12, 15 + $index),
                    'check_in_method' => 'manual',
                    'check_out_method' => 'manual',
                    'check_in_location' => 'Ruang Meeting 1',
                    'check_out_location' => 'Ruang Meeting 1',
                    'status' => 'checked_out',
                    'checked_in_by' => $user?->id,
                    'checked_out_by' => $user?->id,
                ]);

                // Day 2 session (ongoing, only checked in)
                EventAttendance::create([
                    'event_id' => $event2->id,
                    'user_id' => $participant->id,
                    'event_session_id' => $session2_2->id,
                    'check_in_at' => now()->setTime(8, 45 + $index * 3),
                    'check_in_method' => 'qr_code',
                    'check_in_location' => 'Ruang Meeting 1',
                    'status' => 'checked_in',
                    'checked_in_by' => $user?->id,
                ]);
            }

            // Add some variations for day 3 (upcoming)
            if ($users->count() >= 3) {
                // Pre-registered participants
                foreach ($users->take(3) as $participant) {
                    EventAttendance::create([
                        'event_id' => $event2->id,
                        'user_id' => $participant->id,
                        'event_session_id' => $session2_3->id,
                        'status' => 'registered',
                    ]);
                }
            }
        }

        // Event 3: Planned Event
        $event3 = Event::create([
            'code' => 'EVT-2025-003',
            'name' => 'Sertifikasi Penerapan IT untuk Artikel Ilmiah',
            'scheme_id' => $scheme?->id,
            'description' => 'Event sertifikasi untuk spesialis publikasi digital',
            'event_type' => 'certification',
            'start_date' => now()->addDays(60),
            'end_date' => now()->addDays(61),
            'registration_start' => now()->addDays(10),
            'registration_end' => now()->addDays(55),
            'max_participants' => 20,
            'current_participants' => 0,
            'registration_fee' => 3000000,
            'status_id' => $plannedStatus->id,
            'is_published' => false,
            'is_active' => true,
            'location' => 'Surabaya',
            'location_address' => 'Universitas Airlangga, Surabaya',
            'created_by' => $user?->id,
            'updated_by' => $user?->id,
        ]);

        // Event 3 Sessions (basic planning)
        EventSession::create([
            'event_id' => $event3->id,
            'session_code' => 'EXAM',
            'name' => 'Sesi Ujian Kompetensi',
            'session_date' => now()->addDays(60),
            'start_time' => '09:00',
            'end_time' => '15:00',
            'room' => 'TBD',
            'description' => 'Ujian kompetensi penerapan IT',
            'max_participants' => 20,
            'status' => 'scheduled',
            'order' => 1,
        ]);

        // Event 3 TUK Assignment (assigned, waiting confirmation)
        if ($tuks->isNotEmpty()) {
            EventTuk::create([
                'event_id' => $event3->id,
                'tuk_id' => $tuks->first()->id,
                'status' => 'assigned',
                'notes' => 'Menunggu konfirmasi ketersediaan',
            ]);
        }

        $this->command->info('✓ Created 3 events with complete data');
        $this->command->info('  - EVT-2025-001: 4 sessions, 1 TUK confirmed, 3 assessors, 2 materials');
        $this->command->info('  - EVT-2025-002: 3 sessions, 1 TUK confirmed, 1 material, 13 attendance records');
        $this->command->info('  - EVT-2025-003: 1 session, 1 TUK assigned (planned event)');
    }
}
