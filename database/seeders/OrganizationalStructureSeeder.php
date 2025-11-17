<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationalPosition;

class OrganizationalStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Level 1: Dewan Pengarah (Root)
        $dewanPengarah = OrganizationalPosition::create([
            'name' => 'Dewan Pengarah',
            'position' => 'Dewan Pengarah',
            'level' => 'Ketua',
            'parent_id' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        // Level 2: Direktur Utama
        $direkturUtama = OrganizationalPosition::create([
            'name' => 'Dwi Fajar Saputra',
            'position' => 'Direktur Utama',
            'level' => 'Ketua',
            'parent_id' => $dewanPengarah->id,
            'order' => 1,
            'is_active' => true,
        ]);

        // Level 3: Para Direktur (Children of Direktur Utama)
        $komiteSkema = OrganizationalPosition::create([
            'name' => 'Muh Ilham Bakhtiar',
            'position' => 'Komite Skema',
            'level' => 'Koordinator',
            'parent_id' => $direkturUtama->id,
            'order' => 1,
            'is_active' => true,
        ]);

        $direkturManajemenMutu = OrganizationalPosition::create([
            'name' => 'Jamiludin Usman',
            'position' => 'Direktur Manajemen Mutu',
            'level' => 'Wakil Ketua',
            'parent_id' => $direkturUtama->id,
            'order' => 2,
            'is_active' => true,
        ]);

        $direkturAdministrasi = OrganizationalPosition::create([
            'name' => 'Amardyasta Galih Pratama',
            'position' => 'Direktur Administrasi',
            'level' => 'Sekretaris',
            'parent_id' => $direkturUtama->id,
            'order' => 3,
            'is_active' => true,
        ]);

        $direkturKeuangan = OrganizationalPosition::create([
            'name' => 'Furaida Khasanah',
            'position' => 'Direktur Keuangan',
            'level' => 'Bendahara',
            'parent_id' => $direkturUtama->id,
            'order' => 4,
            'is_active' => true,
        ]);

        $direkturSertifikasi = OrganizationalPosition::create([
            'name' => 'Zulidyana Dwi Rusnalasari',
            'position' => 'Direktur Sertifikasi',
            'level' => 'Koordinator',
            'parent_id' => $direkturUtama->id,
            'order' => 5,
            'is_active' => true,
        ]);

        // Level 4: Manajer Representatif (Child of Direktur Manajemen Mutu)
        OrganizationalPosition::create([
            'name' => 'Yusuf Saefudin',
            'position' => 'Manajer Representatif',
            'level' => 'Anggota',
            'parent_id' => $direkturManajemenMutu->id,
            'order' => 1,
            'is_active' => true,
        ]);
    }
}
