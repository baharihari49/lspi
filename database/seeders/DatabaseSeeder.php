<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterDataSeeder::class,  // Must run first for roles, permissions, statuses
            AdminUserSeeder::class,
            OrganizationalStructureSeeder::class,
            NewsSeeder::class,
            LspTukSeeder::class,
            AssessorSeeder::class,  // Must run before EventSeeder
            SchemeSeeder::class,
            EventSeeder::class,
            AssesseeSeeder::class,  // Assessees with documents, employment, education, experience
        ]);
    }
}
