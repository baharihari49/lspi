<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Scheme;
use App\Models\MasterStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        // Event 1: Upcoming Certification Event
        Event::create([
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

        // Event 2: Ongoing Training
        Event::create([
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

        // Event 3: Planned Event
        Event::create([
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

        $this->command->info('✓ Created 3 events');
        $this->command->info('  - EVT-2025-001: Upcoming (Open for Registration)');
        $this->command->info('  - EVT-2025-002: Ongoing Workshop');
        $this->command->info('  - EVT-2025-003: Planned (Not Published)');
    }
}
