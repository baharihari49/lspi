<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Article 1: Pentingnya Sertifikasi Profesi
        News::create([
            'title' => 'Pentingnya Sertifikasi Profesi di Era Digital',
            'slug' => 'pentingnya-sertifikasi-profesi-di-era-digital',
            'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&h=630&fit=crop',
            'excerpt' => 'Sertifikasi profesi menjadi kunci penting dalam meningkatkan kompetensi dan kredibilitas profesional di bidang pengelolaan pustaka elektronik.',
            'content' => '<h1>Mengapa Sertifikasi Profesi Penting?</h1><p>Di era digital yang terus berkembang, sertifikasi profesi menjadi aspek krusial dalam pengembangan karir, terutama bagi para profesional di bidang pengelolaan pustaka ilmiah elektronik.</p><p><strong>Manfaat Sertifikasi Profesi:</strong></p><ul><li>Meningkatkan kredibilitas dan kompetensi profesional</li><li>Memberikan pengakuan formal atas keahlian yang dimiliki</li><li>Membuka peluang karir yang lebih luas</li><li>Meningkatkan standar layanan perpustakaan digital</li></ul><p>LSP-PIE hadir sebagai lembaga yang memberikan sertifikasi berkualitas untuk para profesional di bidang pustaka ilmiah elektronik, memastikan bahwa setiap profesional memiliki kompetensi yang sesuai dengan standar industri.</p><p>Dengan mengikuti program sertifikasi, Anda tidak hanya mendapatkan pengakuan formal, tetapi juga meningkatkan kemampuan dalam mengelola jurnal elektronik, repositori digital, dan sistem perpustakaan modern.</p>',
            'category' => 'Artikel',
            'icon' => 'workspace_premium',
            'icon_color' => 'blue',
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(5),
        ]);

        // Article 2: Tips Pengelolaan Jurnal Elektronik
        News::create([
            'title' => '5 Tips Efektif Mengelola Jurnal Elektronik',
            'slug' => '5-tips-efektif-mengelola-jurnal-elektronik',
            'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1200&h=630&fit=crop',
            'excerpt' => 'Panduan praktis untuk meningkatkan efisiensi pengelolaan jurnal elektronik dengan strategi yang telah terbukti efektif.',
            'content' => '<h1>Tips Mengelola Jurnal Elektronik dengan Efektif</h1><p>Pengelolaan jurnal elektronik yang baik memerlukan pemahaman mendalam tentang sistem, prosedur, dan teknologi terkini. Berikut adalah 5 tips yang dapat membantu Anda:</p><p><strong>1. Implementasi Sistem Manajemen yang Tepat</strong></p><p>Pilih platform OJS (Open Journal Systems) atau sistem sejenis yang sesuai dengan kebutuhan institusi. Pastikan sistem mudah digunakan oleh editor, reviewer, dan penulis.</p><p><strong>2. Standarisasi Metadata</strong></p><p>Gunakan standar metadata yang konsisten seperti Dublin Core atau MARC21 untuk memudahkan pencarian dan indexing artikel.</p><p><strong>3. Proses Review yang Terstruktur</strong></p><p>Tetapkan timeline yang jelas untuk proses peer review, dan gunakan sistem tracking untuk memantau progress setiap submission.</p><p><strong>4. Backup dan Preservasi Digital</strong></p><p>Lakukan backup rutin dan implementasi strategi preservasi digital jangka panjang untuk memastikan aksesibilitas konten di masa depan.</p><p><strong>5. Pelatihan Berkelanjutan</strong></p><p>Ikuti pelatihan dan sertifikasi untuk meningkatkan kompetensi tim dalam mengelola jurnal elektronik secara profesional.</p>',
            'category' => 'Tips & Trik',
            'icon' => 'lightbulb',
            'icon_color' => 'orange',
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(3),
        ]);

        // Article 3: Tren Industri Perpustakaan Digital
        News::create([
            'title' => 'Tren Perpustakaan Digital 2025: Transformasi Menuju Smart Library',
            'slug' => 'tren-perpustakaan-digital-2025-transformasi-menuju-smart-library',
            'image' => 'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=1200&h=630&fit=crop',
            'excerpt' => 'Eksplorasi tren terkini dalam transformasi perpustakaan digital menuju konsep smart library yang lebih interaktif dan berbasis AI.',
            'content' => '<h1>Transformasi Perpustakaan Digital di Tahun 2025</h1><p>Industri perpustakaan mengalami transformasi signifikan dengan adopsi teknologi terkini. Tahun 2025 menandai era baru perpustakaan digital yang lebih cerdas dan responsif.</p><p><strong>Tren Utama Smart Library:</strong></p><ul><li><strong>AI-Powered Search:</strong> Sistem pencarian berbasis artificial intelligence yang memahami konteks dan memberikan rekomendasi personal</li><li><strong>Cloud-Based Infrastructure:</strong> Migrasi ke infrastruktur cloud untuk akses yang lebih fleksibel dan skalabilitas tinggi</li><li><strong>Open Access Movement:</strong> Peningkatan adopsi model open access untuk demokratisasi ilmu pengetahuan</li><li><strong>Analytics & Metrics:</strong> Penggunaan analytics untuk memahami pola penggunaan dan meningkatkan layanan</li></ul><p><strong>Dampak pada Profesional Perpustakaan</strong></p><p>Transformasi ini menuntut profesional perpustakaan untuk terus meningkatkan kompetensi digital mereka. Sertifikasi profesi menjadi semakin penting untuk memastikan bahwa pustakawan memiliki skill yang relevan dengan perkembangan teknologi.</p><p>LSP-PIE berkomitmen untuk mendukung pengembangan kompetensi profesional melalui program sertifikasi yang up-to-date dengan tren industri terkini.</p>',
            'category' => 'Industri',
            'icon' => 'trending_up',
            'icon_color' => 'green',
            'is_published' => true,
            'published_at' => Carbon::now()->subDay(),
        ]);
    }
}
