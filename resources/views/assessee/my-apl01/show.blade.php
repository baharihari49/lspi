@extends('layouts.admin')

@section('title', 'APL-01 - ' . $apl01->form_number)

@php $active = 'my-apl01'; @endphp

@section('page_title', $apl01->form_number)
@section('page_description', 'Detail Formulir APL-01')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Status Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Status Form</h3>
                    @if(in_array($apl01->status, ['draft', 'revised']))
                        <a href="{{ route('admin.my-apl01.edit', $apl01) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Form</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'revised' => 'bg-orange-100 text-orange-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'under_review' => 'Under Review',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'revised' => 'Perlu Revisi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$apl01->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$apl01->status] ?? ucfirst($apl01->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Review Level</p>
                        <p class="font-semibold text-gray-900">
                            @if($apl01->current_review_level > 0)
                                Level {{ $apl01->current_review_level }}
                            @else
                                Belum Dimulai
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Kelengkapan</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $apl01->completion_percentage ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $apl01->completion_percentage ?? 0 }}%</span>
                        </div>
                    </div>
                </div>

                @if($apl01->submitted_at)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600">Disubmit: {{ $apl01->submitted_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>

            <!-- Status Alert -->
            @if($apl01->status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-600">error</span>
                        <div>
                            <h3 class="font-semibold text-red-800">Form Ditolak</h3>
                            <p class="text-sm text-red-700 mt-1">Form APL-01 Anda telah ditolak. Silakan lihat catatan reviewer untuk perbaikan.</p>
                        </div>
                    </div>
                </div>
            @elseif($apl01->status === 'revised')
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-orange-600">info</span>
                        <div>
                            <h3 class="font-semibold text-orange-800">Perlu Revisi</h3>
                            <p class="text-sm text-orange-700 mt-1">Form APL-01 Anda memerlukan revisi. Silakan edit dan submit ulang.</p>
                        </div>
                    </div>
                </div>
            @elseif($apl01->status === 'approved')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <div>
                            <h3 class="font-semibold text-green-800">Form Disetujui</h3>
                            <p class="text-sm text-green-700 mt-1">Form APL-01 Anda telah disetujui. Silakan lanjutkan ke pengisian APL-02.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assessee Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Peserta</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($apl01->full_name ?? 'NA', 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $apl01->full_name ?? '-' }}</h4>
                            <p class="text-sm text-gray-600">{{ $apl01->id_number ?? '-' }}</p>
                            @if($apl01->assessee)
                                <p class="text-sm text-gray-600">{{ $apl01->assessee->registration_number ?? '' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($apl01->email)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">mail</span>
                                <div>
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->mobile)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                                <div>
                                    <p class="text-xs text-gray-600">No. HP</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->mobile }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->city)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">location_on</span>
                                <div>
                                    <p class="text-xs text-gray-600">Lokasi</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->city }}{{ $apl01->province ? ', ' . $apl01->province : '' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->date_of_birth)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">cake</span>
                                <div>
                                    <p class="text-xs text-gray-600">Tempat, Tanggal Lahir</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $apl01->place_of_birth ? $apl01->place_of_birth . ', ' : '' }}{{ $apl01->date_of_birth->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->gender)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">person</span>
                                <div>
                                    <p class="text-xs text-gray-600">Jenis Kelamin</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->nationality)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">flag</span>
                                <div>
                                    <p class="text-xs text-gray-600">Kewarganegaraan</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->nationality }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->current_company)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">business</span>
                                <div>
                                    <p class="text-xs text-gray-600">Perusahaan</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->current_company }}</p>
                                    @if($apl01->current_position)
                                        <p class="text-xs text-gray-500">{{ $apl01->current_position }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($apl01->current_industry)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">category</span>
                                <div>
                                    <p class="text-xs text-gray-600">Industri</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->current_industry }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($apl01->address)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Alamat Lengkap</p>
                            <p class="text-sm text-gray-700">{{ $apl01->address }}{{ $apl01->city ? ', ' . $apl01->city : '' }}{{ $apl01->province ? ', ' . $apl01->province : '' }}{{ $apl01->postal_code ? ' ' . $apl01->postal_code : '' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Scheme Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Skema Sertifikasi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Nama Skema</p>
                            <p class="font-semibold text-gray-900">{{ $apl01->scheme->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">tag</span>
                        <div>
                            <p class="text-xs text-gray-600">Kode Skema</p>
                            <p class="font-semibold text-gray-900">{{ $apl01->scheme->code ?? '-' }}</p>
                        </div>
                    </div>

                    @if($apl01->event)
                        <div class="md:col-span-2 flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Event</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->event->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl01->tuk)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600">apartment</span>
                            <div>
                                <p class="text-xs text-gray-600">TUK (Tempat Uji Kompetensi)</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->tuk->name }}</p>
                                @if($apl01->tuk->city || $apl01->tuk->province)
                                    <p class="text-xs text-gray-500">{{ $apl01->tuk->city }}{{ $apl01->tuk->city && $apl01->tuk->province ? ', ' : '' }}{{ $apl01->tuk->province }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($apl01->eventSession)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-purple-600">calendar_month</span>
                            <div>
                                <p class="text-xs text-gray-600">Jadwal/Sesi</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->eventSession->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($apl01->eventSession->session_date)->format('d M Y') }}
                                    • {{ \Carbon\Carbon::parse($apl01->eventSession->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($apl01->eventSession->end_time)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($apl01->certification_purpose)
                    <div class="pt-4 border-t border-gray-200 mt-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tujuan Sertifikasi</p>
                            <p class="text-sm text-gray-700">{{ $apl01->certification_purpose }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Review History -->
            @if($apl01->reviews && $apl01->reviews->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Review ({{ $apl01->reviews->count() }})</h3>

                    <div class="space-y-4">
                        @foreach($apl01->reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900">{{ $review->review_level_name ?? 'Level ' . $review->review_level }}</span>
                                            @if($review->is_current)
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">Current</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">Reviewer: {{ $review->reviewer->name ?? 'Reviewer' }}</p>
                                    </div>
                                    @php
                                        $decisionColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'approved_with_notes' => 'bg-blue-100 text-blue-800',
                                            'returned' => 'bg-orange-100 text-orange-800',
                                            'forwarded' => 'bg-purple-100 text-purple-800',
                                            'on_hold' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $decisionLabels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'approved_with_notes' => 'Disetujui dengan Catatan',
                                            'returned' => 'Dikembalikan',
                                            'forwarded' => 'Diteruskan',
                                            'on_hold' => 'Ditunda',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $decisionLabels[$review->decision] ?? ucfirst($review->decision) }}
                                    </span>
                                </div>
                                @if($review->review_notes)
                                    <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                        <p class="text-sm text-gray-700">{{ $review->review_notes }}</p>
                                    </div>
                                @endif
                                <div class="flex gap-4 mt-3 text-xs text-gray-500">
                                    <span>Ditugaskan: {{ $review->assigned_at?->format('d M Y H:i') }}</span>
                                    @if($review->completed_at)
                                        <span>Selesai: {{ $review->completed_at->format('d M Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Declaration Status -->
            @if($apl01->declaration_agreed)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
                        <div>
                            <p class="font-medium text-green-900">Deklarasi Telah Disetujui</p>
                            <p class="text-sm text-green-700">Ditandatangani: {{ $apl01->declaration_signed_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-yellow-600 mr-3">warning</span>
                        <div>
                            <p class="font-medium text-yellow-900">Deklarasi Belum Disetujui</p>
                            <p class="text-sm text-yellow-700">Deklarasi harus disetujui sebelum submit</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>

                <div class="space-y-3">
                    @if(in_array($apl01->status, ['draft', 'revised']))
                        <a href="{{ route('admin.my-apl01.edit', $apl01) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Edit Form
                        </a>
                    @endif

                    @if($apl01->status === 'draft')
                        <form action="{{ route('admin.my-apl01.submit', $apl01) }}" method="POST" onsubmit="return confirm('Submit form ini untuk direview?')">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all">
                                Submit untuk Review
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.my-apl01.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Kembali ke Daftar
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Dibuat:</span><br>
                        {{ $apl01->created_at->format('d M Y H:i') }}
                    </p>
                    @if($apl01->updated_at != $apl01->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Terakhir Diupdate:</span><br>
                            {{ $apl01->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Review Level</span>
                        <span class="font-bold text-gray-900">{{ $apl01->current_review_level > 0 ? 'Level ' . $apl01->current_review_level : 'Belum Dimulai' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Review</span>
                        <span class="font-bold text-gray-900">{{ $apl01->reviews ? $apl01->reviews->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Kelengkapan</span>
                        <span class="font-bold text-gray-900">{{ $apl01->completion_percentage ?? 0 }}%</span>
                    </div>
                </div>
            </div>

            <!-- Event Info -->
            @if($apl01->event)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Info Event</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Nama Event</p>
                            <p class="font-semibold text-gray-900">{{ $apl01->event->name }}</p>
                        </div>
                        @if($apl01->event->start_date)
                            <div>
                                <p class="text-xs text-gray-600">Tanggal</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->event->start_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if($apl01->event->location)
                            <div>
                                <p class="text-xs text-gray-600">Lokasi</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->event->location }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
