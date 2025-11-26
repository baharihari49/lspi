# Flow Operasional LSP-PIE

Dokumentasi lengkap alur operasional sistem sertifikasi profesi LSP-PIE.

---

## Daftar Isi

1. [Overview Sistem](#overview-sistem)
2. [Role & Akses](#role--akses)
3. [Flow Utama Sertifikasi](#flow-utama-sertifikasi)
4. [Detail Setiap Tahap](#detail-setiap-tahap)
5. [Panduan Per Role](#panduan-per-role)

---

## Overview Sistem

### Diagram Flow Utama

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         FLOW SERTIFIKASI LSP-PIE                            │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────┐    ┌─────────┐    ┌─────────┐    ┌──────────┐    ┌─────────────┐
│  EVENT  │───▶│  APL-01 │───▶│  APL-02 │───▶│ASSESSMENT│───▶│ CERTIFICATE │
│ (Admin) │    │(Assessee)│   │(Assessee)│   │(Assessor)│    │   (Admin)   │
└─────────┘    └─────────┘    └─────────┘    └──────────┘    └─────────────┘
                   │              │              │
                   ▼              ▼              ▼
              ┌─────────┐    ┌─────────┐    ┌──────────┐
              │ REVIEW  │    │ REVIEW  │    │ APPROVAL │
              │ (Admin) │    │(Assessor)│   │  (Admin) │
              └─────────┘    └─────────┘    └──────────┘
```

### Status Flow

```
EVENT          : draft → published → ongoing → completed → archived
APL-01         : draft → submitted → under_review → approved/rejected
APL-02 Unit    : not_started → in_progress → submitted → reviewed → competent/not_yet_competent
ASSESSMENT     : scheduled → in_progress → completed → approved
CERTIFICATE    : draft → issued → active → expired/revoked
```

---

## Role & Akses

### 1. Super Admin
- Akses penuh ke semua fitur
- Manajemen user, role, permission
- Master data configuration
- Melihat semua data di sistem

### 2. Admin
- Manajemen konten (berita, pengumuman)
- Manajemen LSP (profile, settings)
- Manajemen TUK, Assessor, Assessee
- Manajemen Scheme & Event
- Review APL-01
- Approval hasil asesmen
- Penerbitan sertifikat
- Manajemen pembayaran

### 3. Assessor
- Melihat review yang di-assign
- Review APL-02 portfolio
- Melaksanakan asesmen
- Input hasil asesmen
- Melihat jadwal asesmen

### 4. Assessee (Peserta)
- Mendaftar event sertifikasi
- Mengisi APL-01 (formulir pendaftaran)
- Mengisi APL-02 (asesmen mandiri)
- Mengikuti asesmen
- Melihat hasil & sertifikat
- Upload bukti pembayaran

### 5. TUK Admin
- Manajemen TUK yang ditugaskan
- Manajemen fasilitas TUK
- Manajemen jadwal TUK

---

## Flow Utama Sertifikasi

### Tahap 1: Persiapan (Admin)

```
┌────────────────────────────────────────────────────────────┐
│                    PERSIAPAN ADMIN                         │
├────────────────────────────────────────────────────────────┤
│  1. Setup Scheme (Skema Sertifikasi)                       │
│     └── Tambah unit kompetensi                             │
│     └── Tambah elemen & kriteria                           │
│                                                            │
│  2. Setup TUK (Tempat Uji Kompetensi)                      │
│     └── Data TUK                                           │
│     └── Fasilitas                                          │
│     └── Jadwal ketersediaan                                │
│                                                            │
│  3. Setup Assessor                                         │
│     └── Data assessor                                      │
│     └── Lingkup kompetensi                                 │
│     └── Dokumen & pengalaman                               │
│                                                            │
│  4. Buat Event Sertifikasi                                 │
│     └── Pilih scheme                                       │
│     └── Pilih TUK                                          │
│     └── Set jadwal & kuota                                 │
│     └── Publish event                                      │
└────────────────────────────────────────────────────────────┘
```

### Tahap 2: Pendaftaran Assessee

```
┌────────────────────────────────────────────────────────────┐
│                  PENDAFTARAN ASSESSEE                      │
├────────────────────────────────────────────────────────────┤
│  1. Assessee mendaftar akun                                │
│     URL: /register                                         │
│                                                            │
│  2. Assessee melihat event tersedia                        │
│     Menu: Sertifikasi Saya → Event Sertifikasi             │
│     URL: /admin/available-events                           │
│                                                            │
│  3. Assessee mendaftar ke event                            │
│     Klik "Daftar" pada event                               │
│     → Sistem buat APL-01 dengan status "draft"             │
│                                                            │
│  4. Assessee mengisi APL-01                                │
│     Menu: Sertifikasi Saya → APL-01 Saya                   │
│     URL: /admin/my-apl01                                   │
│     - Isi data pribadi                                     │
│     - Isi data pendidikan                                  │
│     - Isi data pekerjaan                                   │
│     - Upload dokumen pendukung                             │
│     - Centang pernyataan/deklarasi                         │
│                                                            │
│  5. Assessee submit APL-01                                 │
│     Klik "Submit"                                          │
│     → Status berubah: draft → submitted                    │
└────────────────────────────────────────────────────────────┘
```

### Tahap 3: Review APL-01 (Admin)

```
┌────────────────────────────────────────────────────────────┐
│                   REVIEW APL-01 (ADMIN)                    │
├────────────────────────────────────────────────────────────┤
│  1. Admin melihat APL-01 yang submitted                    │
│     Menu: APL-01 Forms → Reviews                           │
│     URL: /admin/apl01-reviews                              │
│     → Muncul alert "X Form Menunggu Diterima"              │
│                                                            │
│  2. Admin accept form untuk review                         │
│     Klik tombol "Accept"                                   │
│     → Status: submitted → under_review                     │
│     → Review record dibuat                                 │
│                                                            │
│  3. Admin melakukan review                                 │
│     Klik ikon "rate_review" (hijau)                        │
│     URL: /admin/apl01-reviews/{id}/review                  │
│     - Periksa kelengkapan data                             │
│     - Periksa dokumen                                      │
│     - Beri catatan/feedback                                │
│                                                            │
│  4. Admin submit keputusan                                 │
│     Pilihan:                                               │
│     ✓ Approve → Status: approved                           │
│       → TRIGGER: Generate APL-02 otomatis                  │
│     ✗ Reject → Status: rejected                            │
│     ↩ Return → Status: revised (assessee perbaiki)         │
└────────────────────────────────────────────────────────────┘
```

### Tahap 4: APL-02 Asesmen Mandiri (Assessee)

```
┌────────────────────────────────────────────────────────────┐
│              APL-02 ASESMEN MANDIRI (ASSESSEE)             │
├────────────────────────────────────────────────────────────┤
│  Setelah APL-01 approved, sistem auto-generate APL-02      │
│  untuk setiap unit kompetensi dalam skema.                 │
│                                                            │
│  1. Assessee melihat APL-02                                │
│     Menu: Sertifikasi Saya → APL-02 Saya                   │
│     URL: /admin/my-apl02                                   │
│                                                            │
│  2. Untuk setiap unit kompetensi:                          │
│     a. Buka unit                                           │
│     b. Isi asesmen mandiri per elemen:                     │
│        - Kompeten (K) / Belum Kompeten (BK)                │
│        - Bukti/evidence yang dimiliki                      │
│     c. Upload bukti pendukung                              │
│        - Sertifikat                                        │
│        - Portofolio                                        │
│        - Dokumen kerja                                     │
│        - dll                                               │
│     d. Submit unit                                         │
│        → Status unit: submitted                            │
│                                                            │
│  3. Setelah semua unit di-submit                           │
│     → TRIGGER: Notifikasi ke Assessor                      │
└────────────────────────────────────────────────────────────┘
```

### Tahap 5: Review APL-02 (Assessor)

```
┌────────────────────────────────────────────────────────────┐
│                REVIEW APL-02 (ASSESSOR)                    │
├────────────────────────────────────────────────────────────┤
│  1. Admin assign assessor ke APL-02 unit                   │
│     Menu: APL-02 Portfolio → Portfolio Units               │
│     URL: /admin/apl02/units                                │
│                                                            │
│  2. Assessor melihat review yang di-assign                 │
│     Menu: Review APL-02 → My APL-02 Reviews                │
│     URL: /admin/apl02/reviews/my-reviews                   │
│                                                            │
│  3. Assessor melakukan review                              │
│     - Periksa asesmen mandiri                              │
│     - Periksa bukti/evidence                               │
│     - Validasi kesesuaian                                  │
│                                                            │
│  4. Assessor submit keputusan per unit                     │
│     Pilihan:                                               │
│     ✓ Kompeten (K)                                         │
│     ✗ Belum Kompeten (BK)                                  │
│     ↩ Perlu klarifikasi                                    │
│                                                            │
│  5. Setelah semua unit direview:                           │
│     Jika SEMUA unit Kompeten:                              │
│     → TRIGGER: Schedule Assessment otomatis                │
└────────────────────────────────────────────────────────────┘
```

### Tahap 6: Pelaksanaan Asesmen (Assessor)

```
┌────────────────────────────────────────────────────────────┐
│              PELAKSANAAN ASESMEN (ASSESSOR)                │
├────────────────────────────────────────────────────────────┤
│  1. Admin/Sistem menjadwalkan asesmen                      │
│     Menu: Assessment Module → Assessments                  │
│     URL: /admin/assessments                                │
│     - Tentukan tanggal & waktu                             │
│     - Tentukan TUK                                         │
│     - Assign assessor                                      │
│                                                            │
│  2. Assessor melaksanakan asesmen                          │
│     Metode asesmen:                                        │
│     a. Observasi (pengamatan langsung)                     │
│        Menu: Assessment Module → Observations              │
│     b. Wawancara/Interview                                 │
│        Menu: Assessment Module → Interviews                │
│     c. Verifikasi dokumen                                  │
│        Menu: Assessment Module → Verification              │
│     d. Tes tertulis (jika ada)                             │
│                                                            │
│  3. Assessor input hasil per kriteria (KUK)                │
│     Menu: Assessment Module → Criteria (KUK)               │
│     - Kompeten / Belum Kompeten                            │
│     - Catatan/feedback                                     │
│                                                            │
│  4. Assessor submit hasil akhir                            │
│     Menu: Hasil Asesmen → Results                          │
│     URL: /admin/assessment-results                         │
│     Keputusan akhir:                                       │
│     ✓ KOMPETEN                                             │
│     ✗ BELUM KOMPETEN                                       │
└────────────────────────────────────────────────────────────┘
```

### Tahap 7: Approval Hasil (Admin)

```
┌────────────────────────────────────────────────────────────┐
│                 APPROVAL HASIL (ADMIN)                     │
├────────────────────────────────────────────────────────────┤
│  1. Admin melihat hasil asesmen                            │
│     Menu: Hasil Asesmen → Result Approval                  │
│     URL: /admin/result-approval                            │
│                                                            │
│  2. Admin review hasil                                     │
│     - Periksa kelengkapan asesmen                          │
│     - Periksa konsistensi penilaian                        │
│     - Validasi prosedur                                    │
│                                                            │
│  3. Admin approve/reject hasil                             │
│     ✓ Approve:                                             │
│       → Status: approved                                   │
│       → TRIGGER: Generate Certificate otomatis             │
│     ✗ Reject:                                              │
│       → Kembali ke assessor untuk perbaikan                │
└────────────────────────────────────────────────────────────┘
```

### Tahap 8: Penerbitan Sertifikat (Admin)

```
┌────────────────────────────────────────────────────────────┐
│              PENERBITAN SERTIFIKAT (ADMIN)                 │
├────────────────────────────────────────────────────────────┤
│  1. Sistem auto-generate sertifikat                        │
│     Setelah hasil asesmen di-approve                       │
│                                                            │
│  2. Admin melihat sertifikat                               │
│     Menu: Sertifikasi → Certificates                       │
│     URL: /admin/certificates                               │
│                                                            │
│  3. Admin verifikasi & issue sertifikat                    │
│     - Periksa data sertifikat                              │
│     - Generate nomor sertifikat                            │
│     - Set masa berlaku (3 tahun)                           │
│     - Issue sertifikat                                     │
│     → Status: draft → issued → active                      │
│                                                            │
│  4. Assessee menerima notifikasi                           │
│     - Email notifikasi                                     │
│     - Dapat download sertifikat                            │
│     Menu: Sertifikasi Saya → Sertifikat Saya               │
│     URL: /admin/my-certificates                            │
└────────────────────────────────────────────────────────────┘
```

---

## Detail Setiap Tahap

### APL-01: Form Permohonan Sertifikasi

#### Data yang Diisi:
| Section | Field | Keterangan |
|---------|-------|------------|
| Data Pribadi | Nama Lengkap | Sesuai KTP |
| | NIK | 16 digit |
| | Tempat/Tanggal Lahir | |
| | Jenis Kelamin | L/P |
| | Alamat | Lengkap dengan RT/RW |
| | No. HP | Aktif untuk WhatsApp |
| | Email | Aktif untuk notifikasi |
| Pendidikan | Pendidikan Terakhir | SD/SMP/SMA/D3/S1/S2/S3 |
| | Nama Institusi | |
| | Tahun Lulus | |
| | Jurusan | |
| Pekerjaan | Nama Perusahaan/Instansi | |
| | Jabatan | |
| | Alamat Kantor | |
| | Lama Bekerja | Dalam tahun |
| Dokumen | Foto KTP | JPG/PNG max 2MB |
| | Foto Ijazah | JPG/PNG/PDF max 2MB |
| | Pas Foto | 3x4, background merah |
| | Dokumen Pendukung | Opsional |
| Deklarasi | Pernyataan | Checkbox persetujuan |

#### Status APL-01:
| Status | Deskripsi | Aksi Selanjutnya |
|--------|-----------|------------------|
| `draft` | Sedang diisi assessee | Assessee submit |
| `submitted` | Sudah disubmit, menunggu accept | Admin accept for review |
| `under_review` | Sedang direview admin | Admin approve/reject |
| `approved` | Disetujui | Auto-generate APL-02 |
| `rejected` | Ditolak | Assessee buat ulang |
| `revised` | Perlu perbaikan | Assessee perbaiki & submit ulang |

---

### APL-02: Asesmen Mandiri

#### Struktur APL-02:
```
APL-02
├── Unit Kompetensi 1
│   ├── Elemen 1
│   │   ├── Kriteria Unjuk Kerja (KUK) 1.1
│   │   ├── KUK 1.2
│   │   └── KUK 1.3
│   ├── Elemen 2
│   │   ├── KUK 2.1
│   │   └── KUK 2.2
│   └── Evidence/Bukti
│       ├── Bukti 1 (file)
│       ├── Bukti 2 (file)
│       └── ...
├── Unit Kompetensi 2
│   └── ...
└── Unit Kompetensi N
    └── ...
```

#### Status APL-02 Unit:
| Status | Deskripsi |
|--------|-----------|
| `not_started` | Belum mulai diisi |
| `in_progress` | Sedang diisi |
| `submitted` | Sudah disubmit, menunggu review |
| `reviewed` | Sudah direview assessor |
| `competent` | Dinyatakan kompeten |
| `not_yet_competent` | Belum kompeten |

---

### Assessment: Pelaksanaan Asesmen

#### Metode Asesmen:
| Metode | Kode | Deskripsi |
|--------|------|-----------|
| Observasi | OB | Pengamatan langsung saat bekerja |
| Demonstrasi | DM | Peragaan keterampilan |
| Pertanyaan Lisan | PL | Wawancara/interview |
| Pertanyaan Tertulis | PT | Tes tertulis |
| Verifikasi Portofolio | VP | Pemeriksaan bukti dokumen |
| Wawancara | WW | Tanya jawab mendalam |

#### Keputusan Asesmen:
| Hasil | Kode | Keterangan |
|-------|------|------------|
| Kompeten | K | Memenuhi semua KUK |
| Belum Kompeten | BK | Ada KUK yang belum terpenuhi |
| Perlu Asesmen Lanjut | AL | Perlu bukti tambahan |

---

## Panduan Per Role

### Panduan Admin

#### Setup Awal
1. **Buat Scheme**
   - Menu: Certification Schemes → Schemes
   - Klik "Create Scheme"
   - Isi: nama, kode, deskripsi
   - Tambah unit kompetensi

2. **Setup TUK**
   - Menu: TUK Management → TUK
   - Klik "Create TUK"
   - Isi: nama, alamat, kapasitas, fasilitas

3. **Tambah Assessor**
   - Menu: Assessor Management → Assessors
   - Klik "Create Assessor"
   - Isi: data diri, lingkup kompetensi

4. **Buat Event**
   - Menu: Event Management → Events
   - Klik "Create Event"
   - Pilih scheme, TUK, set jadwal
   - Publish event

#### Operasional Harian
1. **Review APL-01**
   - Cek menu: APL-01 Forms → Reviews
   - Accept form yang submitted
   - Review dan approve/reject

2. **Monitor APL-02**
   - Cek menu: APL-02 Portfolio → Portfolio Units
   - Assign assessor ke unit

3. **Approval Hasil**
   - Cek menu: Hasil Asesmen → Result Approval
   - Review dan approve hasil

4. **Terbitkan Sertifikat**
   - Cek menu: Sertifikasi → Certificates
   - Issue sertifikat yang sudah ready

---

### Panduan Assessor

#### Review APL-02
1. Login ke sistem
2. Menu: Review APL-02 → My APL-02 Reviews
3. Pilih unit yang akan direview
4. Periksa:
   - Asesmen mandiri assessee
   - Bukti/evidence yang diupload
   - Kesesuaian dengan KUK
5. Beri keputusan: K / BK
6. Submit review

#### Melaksanakan Asesmen
1. Menu: Assessment Module → Assessments
2. Pilih jadwal asesmen
3. Lakukan asesmen sesuai metode:
   - Observasi: Menu → Observations
   - Interview: Menu → Interviews
   - Verifikasi: Menu → Verification
4. Input hasil per KUK
5. Submit hasil akhir

---

### Panduan Assessee

#### Mendaftar Sertifikasi
1. Login ke sistem
2. Menu: Sertifikasi Saya → Event Sertifikasi
3. Pilih event yang sesuai
4. Klik "Daftar"

#### Mengisi APL-01
1. Menu: Sertifikasi Saya → APL-01 Saya
2. Klik form yang berstatus "Draft"
3. Isi semua section:
   - Data Pribadi
   - Data Pendidikan
   - Data Pekerjaan
   - Upload Dokumen
4. Centang pernyataan/deklarasi
5. Klik "Submit"

#### Mengisi APL-02
1. Tunggu APL-01 diapprove
2. Menu: Sertifikasi Saya → APL-02 Saya
3. Untuk setiap unit kompetensi:
   - Klik unit
   - Isi asesmen mandiri (K/BK)
   - Upload bukti
   - Submit unit
4. Tunggu review assessor

#### Mengikuti Asesmen
1. Tunggu jadwal asesmen
2. Datang ke TUK sesuai jadwal
3. Ikuti proses asesmen
4. Tunggu hasil

#### Melihat Hasil & Sertifikat
1. Menu: Sertifikasi Saya → Asesmen Saya
2. Lihat hasil asesmen
3. Jika KOMPETEN:
   - Menu: Sertifikasi Saya → Sertifikat Saya
   - Download sertifikat

---

## URL Reference

### Admin URLs
| Fitur | URL |
|-------|-----|
| Dashboard | `/admin/dashboard` |
| APL-01 List | `/admin/apl01` |
| APL-01 Reviews | `/admin/apl01-reviews` |
| APL-02 Units | `/admin/apl02/units` |
| APL-02 Reviews | `/admin/apl02/reviews` |
| Assessments | `/admin/assessments` |
| Result Approval | `/admin/result-approval` |
| Certificates | `/admin/certificates` |
| Events | `/admin/events` |
| Schemes | `/admin/schemes` |
| Assessors | `/admin/assessors` |
| Assessees | `/admin/assessees` |
| TUK | `/admin/tuk` |
| Users | `/admin/users` |

### Assessee URLs
| Fitur | URL |
|-------|-----|
| Event Tersedia | `/admin/available-events` |
| APL-01 Saya | `/admin/my-apl01` |
| APL-02 Saya | `/admin/my-apl02` |
| Asesmen Saya | `/admin/my-assessments` |
| Sertifikat Saya | `/admin/my-certificates` |
| Pembayaran Saya | `/admin/my-payments` |

### Assessor URLs
| Fitur | URL |
|-------|-----|
| My APL-01 Reviews | `/admin/apl01-reviews/my-reviews` |
| My APL-02 Reviews | `/admin/apl02/reviews/my-reviews` |
| Assessments | `/admin/assessments` |

---

## Catatan Teknis

### Auto-Trigger Events
| Event | Trigger | Action |
|-------|---------|--------|
| `Apl01Approved` | APL-01 di-approve | Generate APL-02 units |
| `Apl02AllUnitsCompetent` | Semua unit APL-02 kompeten | Schedule Assessment |
| `AssessmentResultApproved` | Hasil asesmen di-approve | Generate Certificate |

### Queue Jobs
Sistem menggunakan database queue. Pastikan queue worker berjalan:
```bash
php artisan queue:work
```
Atau gunakan `composer dev` yang sudah include queue worker.

### Notifikasi
Sistem mengirim notifikasi via:
- Email (untuk event penting)
- In-app notification (dashboard)

---

## Troubleshooting

### APL-01 tidak muncul di Reviews
**Masalah:** Form sudah submitted tapi tidak muncul di halaman reviews.
**Solusi:** Admin harus klik "Accept for Review" dulu di `/admin/apl01-reviews`.

### APL-02 tidak ter-generate
**Masalah:** APL-01 sudah approved tapi APL-02 tidak muncul.
**Solusi:**
1. Pastikan queue worker berjalan
2. Atau trigger manual via tinker:
   ```php
   $apl01 = App\Models\Apl01Form::find(ID);
   $service = new App\Services\Apl02GeneratorService();
   $service->generateFromApl01($apl01);
   ```

### Sertifikat tidak ter-generate
**Masalah:** Hasil asesmen sudah approved tapi sertifikat tidak muncul.
**Solusi:**
1. Pastikan queue worker berjalan
2. Cek log di `storage/logs/laravel.log`

---

## Kontak Support

Jika ada pertanyaan atau masalah teknis, hubungi:
- Email: support@lsp-pie.id
- WhatsApp: 08xx-xxxx-xxxx

---

*Dokumentasi ini dibuat untuk LSP-PIE Certification Management System*
*Last updated: November 2025*
