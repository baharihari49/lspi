<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@lsp-pie.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Assign super-admin role
        $superAdminRole = \App\Models\MasterRole::where('slug', 'super-admin')->first();
        $superAdmin->roles()->attach($superAdminRole->id, [
            'assigned_by' => $superAdmin->id,
            'assigned_at' => now(),
        ]);

        // Create Admin
        $admin = User::create([
            'name' => 'Admin LSP-PIE',
            'email' => 'admin@lsp-pie.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $adminRole = \App\Models\MasterRole::where('slug', 'admin')->first();
        $admin->roles()->attach($adminRole->id, [
            'assigned_by' => $superAdmin->id,
            'assigned_at' => now(),
        ]);
    }
}
