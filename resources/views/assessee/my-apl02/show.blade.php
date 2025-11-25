@extends('layouts.admin')

@section('title', 'Detail Unit APL-02')

@php $active = 'my-apl02'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-apl02.index') }}" class="hover:text-blue-600">APL-02 Saya</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>{{ $unit->unit_code }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $unit->unit_title }}</h1>
        </div>
        <div class="flex gap-2">
            @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <span class="material-symbols-outlined text-lg">upload</span>
                    Upload Bukti
                </a>
                @if($unit->evidence && $unit->evidence->count() > 0)
                    <form action="{{ route('admin.my-apl02.submit', $unit) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin submit unit ini untuk direview?')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <span class="material-symbols-outlined text-lg">send</span>
                            Submit
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <!-- Status Info -->
    @php
        $statusColors = [
            'not_started' => 'bg-gray-100 text-gray-800 border-gray-200',
            'in_progress' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            'submitted' => 'bg-blue-50 text-blue-800 border-blue-200',
            'under_review' => 'bg-purple-50 text-purple-800 border-purple-200',
            'competent' => 'bg-green-50 text-green-800 border-green-200',
            'not_yet_competent' => 'bg-red-50 text-red-800 border-red-200',
            'requires_clarification' => 'bg-orange-50 text-orange-800 border-orange-200',
        ];
        $statusLabels = [
            'not_started' => 'Belum Mulai',
            'in_progress' => 'Dalam Proses',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'competent' => 'Kompeten',
            'not_yet_competent' => 'Belum Kompeten',
            'requires_clarification' => 'Perlu Klarifikasi',
        ];
    @endphp
    <div class="p-4 rounded-xl border {{ $statusColors[$unit->status] ?? 'bg-gray-50 border-gray-200' }}">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined">info</span>
            <div>
                <span class="font-semibold">Status: {{ $statusLabels[$unit->status] ?? $unit->status }}</span>
                @if($unit->status === 'requires_clarification')
                    <p class="text-sm mt-1">Silakan lengkapi atau perbaiki bukti yang diminta oleh asesor.</p>
                @elseif($unit->status === 'competent')
                    <p class="text-sm mt-1">Selamat! Unit kompetensi ini telah dinyatakan kompeten.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Unit</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Kode Unit</p>
                        <p class="font-mono font-medium text-gray-900">{{ $unit->unit_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Judul Unit</p>
                        <p class="font-medium text-gray-900">{{ $unit->unit_title }}</p>
                    </div>
                    @if($unit->unit_description)
                        <div>
                            <p class="text-sm text-gray-600">Deskripsi</p>
                            <p class="text-gray-700">{{ $unit->unit_description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Evidence List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Bukti yang Diupload</h2>
                    @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                        <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">add</span>
                            Tambah Bukti
                        </a>
                    @endif
                </div>

                @if($unit->evidence && $unit->evidence->count() > 0)
                    <div class="space-y-4">
                        @foreach($unit->evidence as $evidence)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-gray-100 rounded-lg">
                                            @php
                                                $iconMap = [
                                                    'document' => 'description',
                                                    'certificate' => 'workspace_premium',
                                                    'photo' => 'image',
                                                    'video' => 'videocam',
                                                    'portfolio' => 'folder',
                                                ];
                                            @endphp
                                            <span class="material-symbols-outlined text-gray-600">{{ $iconMap[$evidence->evidence_type] ?? 'attach_file' }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $evidence->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $evidence->description }}</p>
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                                <span>{{ ucfirst($evidence->evidence_type) }}</span>
                                                <span>{{ $evidence->file_name }}</span>
                                                @if($evidence->file_size)
                                                    <span>{{ number_format($evidence->file_size / 1024, 1) }} KB</span>
                                                @endif
                                            </div>
                                            <!-- Verification Status -->
                                            @php
                                                $verifyColors = [
                                                    'pending' => 'text-yellow-600',
                                                    'verified' => 'text-green-600',
                                                    'rejected' => 'text-red-600',
                                                ];
                                            @endphp
                                            <span class="text-xs {{ $verifyColors[$evidence->verification_status] ?? 'text-gray-500' }}">
                                                {{ ucfirst($evidence->verification_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($evidence->file_path)
                                            <a href="{{ Storage::url($evidence->file_path) }}" target="_blank"
                                                class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                                <span class="material-symbols-outlined text-xl">visibility</span>
                                            </a>
                                        @endif
                                        @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                                            <form action="{{ route('admin.my-apl02.delete-evidence', [$unit, $evidence]) }}" method="POST"
                                                onsubmit="return confirm('Hapus bukti ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">upload_file</span>
                        <p class="mt-2 text-gray-500">Belum ada bukti yang diupload</p>
                        @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                            <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                Upload Bukti Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Progress</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Kelengkapan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $unit->completion_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $unit->completion_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Bukti</span>
                        <span class="font-medium text-gray-900">{{ $unit->total_evidence ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Review Notes -->
            @if($unit->reviews && $unit->reviews->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Catatan Asesor</h2>
                    <div class="space-y-4">
                        @foreach($unit->reviews->sortByDesc('created_at')->take(3) as $review)
                            <div class="border-l-4 border-blue-500 pl-3 py-1">
                                <p class="text-sm text-gray-700">{{ $review->overall_comments }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $review->created_at?->format('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
