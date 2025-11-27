# LSP-PIE
## Sistem Manajemen Sertifikasi Profesi

### Lembaga Sertifikasi Profesi - Pustaka Ilmiah Elektronik

---

## Daftar Isi

1. [Overview Project](#1-overview-project)
2. [Technology Stack](#2-technology-stack)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Database Schema](#4-database-schema)
5. [Modul & Fitur](#5-modul--fitur)
6. [Alur Sertifikasi](#6-alur-sertifikasi)
7. [User Roles](#7-user-roles)
8. [API & Routes](#8-api--routes)
9. [Screenshots](#9-screenshots)
10. [Roadmap](#10-roadmap)

---

## 1. Overview Project

### Apa itu LSP-PIE?

**LSP-PIE (Lembaga Sertifikasi Profesi - Pustaka Ilmiah Elektronik)** adalah sistem manajemen sertifikasi profesi berbasis web yang komprehensif untuk bidang pengelolaan perpustakaan ilmiah elektronik di Indonesia.

### Tujuan Sistem

- Mengelola siklus lengkap sertifikasi profesi
- Dari pendaftaran hingga penerbitan sertifikat
- Memfasilitasi proses asesmen yang terstandar
- Menyediakan tracking dan audit trail yang lengkap

### Target Pengguna

| Role | Deskripsi |
|------|-----------|
| **Super Admin** | Pengelola sistem keseluruhan |
| **Admin LSP** | Staff LSP yang mengelola operasional |
| **Asesor** | Penilai kompetensi yang tersertifikasi |
| **Asesi** | Peserta yang mengikuti sertifikasi |

---

## 2. Technology Stack

### Backend

| Technology | Version | Kegunaan |
|------------|---------|----------|
| **Laravel** | 12 | PHP Framework |
| **PHP** | 8.2+ | Server-side Language |
| **SQLite** | - | Database (dev) |
| **MySQL/PostgreSQL** | - | Database (prod) |

### Frontend

| Technology | Version | Kegunaan |
|------------|---------|----------|
| **Blade** | - | Templating Engine |
| **Tailwind CSS** | v4 | Styling Framework |
| **Vite** | - | Asset Bundler |
| **Material Symbols** | - | Icon Library |

### Infrastructure

| Component | Implementation |
|-----------|----------------|
| **Session** | Database |
| **Cache** | Database |
| **Queue** | Database |
| **Storage** | Local + Cloudinary |
| **Mail** | SMTP / Log |

### Development Tools

- **Laravel Pint** - Code Formatter
- **PHPUnit** - Testing Framework
- **Laravel Pail** - Log Viewer
- **Vite HMR** - Hot Module Reloading

---

## 3. Arsitektur Sistem

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                      │
├─────────────────────────────────────────────────────────────┤
│  Public Pages  │  Admin Panel  │  Assessee Self-Service     │
└────────┬───────┴───────┬───────┴──────────┬─────────────────┘
         │               │                  │
         ▼               ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                      CONTROLLER LAYER                        │
│                      (70 Controllers)                        │
└────────┬───────────────┬──────────────────┬─────────────────┘
         │               │                  │
         ▼               ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                      SERVICE LAYER                           │
├─────────────────────────────────────────────────────────────┤
│ CertificationFlowService │ Apl02GeneratorService            │
│ AssessmentSchedulerService │ CertificateGeneratorService    │
│ FlowNotificationService                                      │
└────────┬───────────────┬──────────────────┬─────────────────┘
         │               │                  │
         ▼               ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                      MODEL LAYER                             │
│                      (71 Eloquent Models)                    │
└────────┬───────────────┬──────────────────┬─────────────────┘
         │               │                  │
         ▼               ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                          │
│                      (95 Migrations)                         │
└─────────────────────────────────────────────────────────────┘
```

### MVC Pattern

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    Routes    │────▶│  Controller  │────▶│    View      │
│  (web.php)   │     │              │     │  (Blade)     │
└──────────────┘     └──────┬───────┘     └──────────────┘
                           │
                           ▼
                    ┌──────────────┐
                    │   Service    │
                    │   (Logic)    │
                    └──────┬───────┘
                           │
                           ▼
                    ┌──────────────┐
                    │    Model     │
                    │  (Eloquent)  │
                    └──────┬───────┘
                           │
                           ▼
                    ┌──────────────┐
                    │   Database   │
                    │   (SQLite)   │
                    └──────────────┘
```

---

## 4. Database Schema

### Entity Relationship Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        USER MANAGEMENT                           │
├─────────────────────────────────────────────────────────────────┤
│  User ─┬─ UserProfile                                           │
│        ├─ UserContact                                           │
│        ├─ MasterRole ─── MasterPermission                       │
│        └─ File                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    CERTIFICATION SCHEME                          │
├─────────────────────────────────────────────────────────────────┤
│  Scheme ─── SchemeVersion ─── SchemeUnit ─── SchemeElement      │
│                    │               │              │              │
│                    │               │              └── SchemeCriterion
│                    │               └── SchemeDocument            │
│                    └── SchemeRequirement                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    CERTIFICATION FLOW                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Assessee ──▶ Apl01Form ──▶ Apl02Unit ──▶ Assessment            │
│                   │            │              │                  │
│                   │            │              ├── AssessmentUnit │
│                   │            │              ├── AssessmentCriteria
│                   │            │              └── AssessmentResult
│                   │            │                      │          │
│                   │            └── Apl02Evidence      │          │
│                   │                                   ▼          │
│                   └── Apl01Review              Certificate       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Tabel Utama (71 Models)

| Domain | Jumlah | Tabel |
|--------|--------|-------|
| **User Management** | 8 | User, UserProfile, UserContact, MasterRole, MasterPermission, MasterStatus, MasterMethod, File |
| **Organization** | 6 | LspProfile, OrgSetting, OrganizationalPosition, Tuk, TukFacility, TukSchedule |
| **Assessor** | 5 | Assessor, AssessorDocument, AssessorCompetencyScope, AssessorExperience, AssessorBankInfo |
| **Assessee** | 5 | Assessee, AssesseeDocument, AssesseeEmploymentInfo, AssesseeEducationHistory, AssesseeExperience |
| **Scheme** | 7 | Scheme, SchemeVersion, SchemeUnit, SchemeElement, SchemeCriterion, SchemeRequirement, SchemeDocument |
| **APL Forms** | 7 | Apl01Form, Apl01FormField, Apl01Answer, Apl01Review, Apl02Unit, Apl02Evidence, Apl02AssessorReview |
| **Event** | 6 | Event, EventSession, EventTuk, EventAssessor, EventMaterial, EventAttendance |
| **Assessment** | 10 | Assessment, AssessmentUnit, AssessmentCriteria, AssessmentObservation, AssessmentDocument, AssessmentInterview, AssessmentVerification, AssessmentFeedback, AssessmentResult, ResultApproval |
| **Certificate** | 5 | Certificate, CertificateQrValidation, CertificateLog, CertificateRevoke, CertificateRenewal |
| **Payment** | 4 | Payment, PaymentMethod, PaymentItem, PaymentStatusHistory |
| **Audit** | 3 | Notification, EmailLog, AuditTrail |

---

## 5. Modul & Fitur

### A. Dashboard & Administrasi

- **Dashboard Admin** - Overview statistik dan aktivitas
- **Module Launcher** - Akses cepat ke semua modul
- **Master Data** - Kelola role, permission, status, method
- **User Management** - Kelola akun pengguna

### B. Manajemen Organisasi

- **Profil LSP** - Informasi lembaga sertifikasi
- **Struktur Organisasi** - Hierarki jabatan
- **TUK Management** - Tempat Uji Kompetensi
  - Daftar fasilitas
  - Jadwal operasional
  - Dokumen pendukung

### C. Manajemen Asesor

```
┌─────────────────────────────────────────────┐
│              ASSESSOR MODULE                 │
├─────────────────────────────────────────────┤
│  ┌─────────────┐   ┌─────────────────────┐  │
│  │   Profile   │   │  Documents          │  │
│  │   - Name    │   │  - KTP              │  │
│  │   - REG No  │   │  - Sertifikat       │  │
│  │   - Contact │   │  - Lisensi          │  │
│  └─────────────┘   └─────────────────────┘  │
│  ┌─────────────┐   ┌─────────────────────┐  │
│  │  Competency │   │  Bank Info          │  │
│  │   Scope     │   │  - Account          │  │
│  │             │   │  - Bank Name        │  │
│  └─────────────┘   └─────────────────────┘  │
│  ┌─────────────────────────────────────────┐│
│  │  Experience                             ││
│  │  - Pengalaman kerja                     ││
│  │  - Riwayat asesmen                      ││
│  └─────────────────────────────────────────┘│
└─────────────────────────────────────────────┘
```

**Fitur:**
- Registrasi dan verifikasi asesor
- Upload dan validasi dokumen
- Assign lingkup kompetensi
- Tracking pengalaman kerja
- Informasi rekening untuk pembayaran

### D. Manajemen Asesi

**Fitur:**
- Registrasi peserta sertifikasi
- Profil lengkap (kontak, pendidikan, pekerjaan)
- Upload dokumen pendukung
- Riwayat pengalaman kerja
- Self-service portal

### E. Skema Sertifikasi

```
Scheme
  └── SchemeVersion (v1.0, v2.0, ...)
        ├── SchemeUnit (Unit 1, Unit 2, ...)
        │     └── SchemeElement (Elemen 1.1, 1.2, ...)
        │           └── SchemeCriterion (KUK 1.1.1, 1.1.2, ...)
        ├── SchemeRequirement (Persyaratan)
        └── SchemeDocument (Template Dokumen)
```

**Fitur:**
- Hierarki skema multi-level
- Version control (support multiple versions)
- Form field builder untuk APL-01
- Definisi persyaratan
- Template dokumen

### F. Event Management

**Fitur:**
- Buat event sertifikasi/pelatihan
- Atur sesi dan jadwal
- Assign TUK dan Asesor
- Upload materi
- Tracking kehadiran (check-in/check-out)
- Manajemen pembayaran

**Status Event:**
| Status | Warna | Deskripsi |
|--------|-------|-----------|
| Draft | Kuning | Event sedang direncanakan |
| Published | Hijau | Event dipublikasikan |
| Ongoing | Biru | Event sedang berlangsung |
| Completed | Indigo | Event selesai |
| Cancelled | Merah | Event dibatalkan |

### G. Formulir APL-01

**Alur APL-01:**
```
Draft ──▶ Submitted ──▶ Under Review ──▶ Approved
                              │              │
                              ▼              ▼
                          Rejected    Generate APL-02
                              │
                              ▼
                          Revision
```

**Fitur:**
- Dynamic form generation per skema
- Auto-fill dari profil asesi
- Multi-level review workflow
- Tracking completion percentage
- Rejection dan revision handling
- Auto-generate nomor: APL-01-YYYY-NNNN

### H. Formulir APL-02

**Fitur:**
- Auto-generate dari APL-01 approved
- Evidence submission per unit
- Asesor verification
- Mapping ke elemen kompetensi
- Progress tracking per unit

### I. Assessment

**Alur Assessment:**
```
Draft ──▶ Scheduled ──▶ In Progress ──▶ Completed
                                            │
                                            ▼
                              Under Review ──▶ Verified ──▶ Approved
                                                              │
                                                              ▼
                                                        Certificate
```

**Metode Assessment:**
- Observasi langsung
- Wawancara
- Review dokumen
- Tes tertulis
- Demonstrasi

### J. Sertifikat

**Fitur:**
- Auto-generate setelah assessment approved
- QR Code untuk verifikasi
- Template customizable
- Validity period tracking
- Revocation workflow
- Renewal workflow
- Public verification endpoint

### K. Pembayaran

**Fitur:**
- Tracking pembayaran event/assessment
- Multiple payment methods
- Upload bukti pembayaran
- Verification workflow
- Invoice & receipt generation
- Status history audit trail

---

## 6. Alur Sertifikasi

### Complete Certification Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    CERTIFICATION FLOW                            │
└─────────────────────────────────────────────────────────────────┘

     ┌──────────┐
     │ ASSESSEE │
     │ Register │
     └────┬─────┘
          │
          ▼
┌─────────────────┐
│   APL-01 Form   │ ◀─── Self-Assessment
│   (Draft)       │      Isian Mandiri
└────────┬────────┘
         │ Submit
         ▼
┌─────────────────┐
│   APL-01 Form   │ ◀─── Admin/Reviewer
│ (Under Review)  │      Verifikasi Data
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌────────┐ ┌────────┐
│Rejected│ │Approved│
└───┬────┘ └───┬────┘
    │          │
    ▼          ▼
┌────────┐ ┌─────────────────┐
│Revision│ │ AUTO-GENERATE   │
└────────┘ │   APL-02        │
           └────────┬────────┘
                    │
                    ▼
           ┌─────────────────┐
           │   APL-02 Unit   │ ◀─── Upload Evidence
           │   Evidence      │      Per Unit Kompetensi
           └────────┬────────┘
                    │ All Units Competent
                    ▼
           ┌─────────────────┐
           │ AUTO-SCHEDULE   │
           │   Assessment    │
           └────────┬────────┘
                    │
                    ▼
           ┌─────────────────┐
           │   Assessment    │ ◀─── Asesor menilai
           │   Execution     │      Observasi/Interview
           └────────┬────────┘
                    │
                    ▼
           ┌─────────────────┐
           │   Assessment    │ ◀─── Multi-level approval
           │   Result        │
           └────────┬────────┘
                    │
               ┌────┴────┐
               │         │
               ▼         ▼
          ┌────────┐ ┌────────┐
          │  NOT   │ │COMPETENT│
          │COMPETENT│ └───┬────┘
          └────────┘     │
                         ▼
                ┌─────────────────┐
                │ AUTO-GENERATE   │
                │   Certificate   │
                └────────┬────────┘
                         │
                         ▼
                ┌─────────────────┐
                │   Certificate   │ ◀─── QR Code
                │   Issued        │      Verification
                └─────────────────┘
```

### Services yang Terlibat

| Service | Fungsi |
|---------|--------|
| **CertificationFlowService** | Orchestrator utama alur sertifikasi |
| **Apl02GeneratorService** | Generate APL-02 units dari APL-01 approved |
| **AssessmentSchedulerService** | Auto-schedule assessment |
| **CertificateGeneratorService** | Generate sertifikat |
| **FlowNotificationService** | Kirim notifikasi di setiap milestone |

---

## 7. User Roles

### Role Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│                       SUPER ADMIN                            │
│  - Full system access                                        │
│  - User management                                           │
│  - System configuration                                      │
└──────────────────────────┬──────────────────────────────────┘
                           │
          ┌────────────────┼────────────────┐
          │                │                │
          ▼                ▼                ▼
┌─────────────────┐ ┌─────────────┐ ┌─────────────────┐
│     ADMIN       │ │   ASSESSOR  │ │    ASSESSEE     │
│                 │ │             │ │                 │
│ - Event mgmt    │ │ - Review    │ │ - Self-service  │
│ - APL review    │ │   APL-02    │ │   APL-01/02     │
│ - Assessment    │ │ - Conduct   │ │ - View results  │
│   oversight     │ │   assessment│ │ - Download cert │
│ - Certificate   │ │ - Submit    │ │ - Payment       │
│   management    │ │   results   │ │                 │
└─────────────────┘ └─────────────┘ └─────────────────┘
```

### Permission Matrix

| Feature | Super Admin | Admin | Assessor | Assessee |
|---------|:-----------:|:-----:|:--------:|:--------:|
| Master Data | Full | View | - | - |
| User Management | Full | Limited | - | - |
| Assessor Management | Full | Full | View Own | - |
| Scheme Management | Full | Full | View | View |
| Event Management | Full | Full | View Assigned | Register |
| APL-01 Management | Full | Review | - | Create/Edit Own |
| APL-02 Management | Full | View | Review | Upload Evidence |
| Assessment | Full | Oversight | Conduct | View Own |
| Certificate | Full | Issue | View | Download Own |
| Payment | Full | Verify | View Own | Pay |

---

## 8. API & Routes

### Route Groups

```php
// Public Routes
Route::get('/', 'HomeController@index');
Route::get('/profile', 'ProfileController@index');
Route::get('/skema', 'SchemeController@index');
Route::get('/news', 'NewsController@index');

// Auth Routes
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/logout', 'AuthController@logout');

// Admin Routes (prefix: /admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index');

    // Master Data
    Route::resource('master-roles', MasterRoleController::class);
    Route::resource('master-statuses', MasterStatusController::class);

    // Assessor Management
    Route::resource('assessors', AssessorController::class);
    Route::resource('assessors.documents', AssessorDocumentController::class);

    // Scheme Management (nested)
    Route::resource('schemes', SchemeController::class);
    Route::resource('schemes.versions', SchemeVersionController::class);
    Route::resource('schemes.versions.units', SchemeUnitController::class);

    // Event Management
    Route::resource('events', EventController::class);
    Route::resource('events.sessions', EventSessionController::class);
    Route::resource('events.assessors', EventAssessorController::class);

    // APL Forms
    Route::resource('apl01', Apl01FormController::class);
    Route::post('apl01/{apl01}/generate-apl02', 'Apl01FormController@generateApl02');

    // Certificates
    Route::resource('certificates', CertificateController::class);
    Route::get('certificates/{certificate}/verify', 'CertificateController@verify');
});

// Assessee Self-Service Routes
Route::middleware(['auth', 'role:assessee'])->prefix('admin')->group(function () {
    Route::get('/my-apl01', 'MyApl01Controller@index');
    Route::get('/my-apl02', 'MyApl02Controller@index');
    Route::get('/my-certificates', 'MyCertificateController@index');
});
```

### Controller Count by Domain

| Domain | Controllers |
|--------|-------------|
| Dashboard & Config | 3 |
| Master Data | 5 |
| Organization | 7 |
| User Management | 1 |
| Content Management | 2 |
| Assessor | 5 |
| Scheme | 7 |
| Event | 6 |
| Assessee | 5 |
| APL-01 | 3 |
| APL-02 | 3 |
| Assessment | 7 |
| Results & Approval | 2 |
| Certificate | 4 |
| Payment | 2 |
| Certification Flow | 1 |
| Assessee Self-Service | 6 |
| **Total** | **70** |

---

## 9. Screenshots

> *Tambahkan screenshots dari aplikasi di sini*

### Dashboard
- Admin Dashboard dengan statistik
- Module Launcher

### Manajemen Asesor
- List Asesor
- Form Tambah/Edit Asesor
- Detail Asesor

### Skema Sertifikasi
- Hierarki Skema
- Unit Kompetensi
- Elemen dan Kriteria

### Event Management
- List Event
- Detail Event
- Assign Asesor

### APL-01 & APL-02
- Form APL-01
- Review Workflow
- Evidence Upload

### Assessment
- Assessment Form
- Scoring Interface
- Result Summary

### Sertifikat
- Certificate View
- QR Code Verification

---

## 10. Roadmap

### Phase 1 - Core System (Completed)
- [x] User Authentication & Authorization
- [x] Master Data Management
- [x] Assessor Management
- [x] Assessee Management
- [x] Scheme Management
- [x] Event Management
- [x] APL-01 Form System
- [x] APL-02 Evidence System
- [x] Assessment Module
- [x] Certificate Generation

### Phase 2 - Enhancement (In Progress)
- [x] Certification Flow Automation
- [x] Multi-language Support (ID/EN)
- [ ] Email Notifications
- [ ] Dashboard Analytics
- [ ] Reporting Module

### Phase 3 - Advanced Features (Planned)
- [ ] Mobile Responsive Optimization
- [ ] API for External Integration
- [ ] Bulk Import/Export
- [ ] Advanced Reporting & Analytics
- [ ] Document Template Designer
- [ ] Multi-tenant Support

### Phase 4 - Integration (Future)
- [ ] BNSP Integration
- [ ] Payment Gateway Integration
- [ ] Digital Signature
- [ ] Blockchain Certificate Verification

---

## Kontak & Support

**LSP-PIE Development Team**

- Website: [lsp-pie.id](https://lsp-pie.id)
- Email: support@lsp-pie.id
- GitHub: [github.com/lsp-pie](https://github.com/lsp-pie)

---

*Dokumen ini dibuat untuk keperluan presentasi project LSP-PIE.*
*Last updated: November 2025*
