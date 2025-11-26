@extends('layouts.admin')

@section('title', 'Detail Unit APL-02')

@php $active = 'my-apl02'; @endphp

@section('page_title', 'Detail Unit APL-02')
@section('page_description', $unit->unit_code . ' - ' . $unit->unit_title)

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
            <!-- Unit Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Unit</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">tag</span>
                        <div>
                            <p class="text-xs text-gray-600">Kode Unit</p>
                            <p class="font-semibold text-gray-900">{{ $unit->unit_code }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Skema</p>
                            <p class="font-semibold text-gray-900">{{ $unit->scheme->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">verified</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            @php
                                $statusColors = [
                                    'not_started' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'submitted' => 'bg-yellow-100 text-yellow-800',
                                    'under_review' => 'bg-purple-100 text-purple-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                    'requires_clarification' => 'bg-orange-100 text-orange-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                ];
                                $statusLabels = [
                                    'not_started' => 'Belum Mulai',
                                    'in_progress' => 'Dalam Proses',
                                    'submitted' => 'Submitted',
                                    'under_review' => 'Under Review',
                                    'competent' => 'Kompeten',
                                    'not_yet_competent' => 'Belum Kompeten',
                                    'requires_clarification' => 'Perlu Klarifikasi',
                                    'completed' => 'Selesai',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$unit->status] ?? ucfirst($unit->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">assessment</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Hasil Asesmen</p>
                            @php
                                $resultColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                    'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                ];
                                $resultLabels = [
                                    'pending' => 'Menunggu',
                                    'competent' => 'Kompeten',
                                    'not_yet_competent' => 'Belum Kompeten',
                                    'requires_more_evidence' => 'Perlu Bukti Tambahan',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $resultColors[$unit->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $resultLabels[$unit->assessment_result] ?? ucfirst($unit->assessment_result) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($unit->unit_description)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-700">{{ $unit->unit_description }}</p>
                    </div>
                @endif

                @if($unit->assessment_notes)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Catatan Asesmen</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $unit->assessment_notes }}</p>
                        </div>
                    </div>
                @endif

                @if($unit->recommendations)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Rekomendasi</p>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-900 whitespace-pre-wrap">{{ $unit->recommendations }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Progress Kelengkapan</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Kelengkapan Keseluruhan</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($unit->completion_percentage, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $unit->completion_percentage }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-900">{{ $unit->total_evidence }}</p>
                            <p class="text-xs text-blue-700 mt-1">Total Bukti</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-900">{{ $unit->schemeUnit?->elements?->count() ?? 0 }}</p>
                            <p class="text-xs text-purple-700 mt-1">Total Elemen</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Portfolio Bukti</h3>
                    @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                        <a href="{{ route('admin.my-apl02.upload', $unit) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">add</span>
                            Tambah Bukti
                        </a>
                    @endif
                </div>

                @if($unit->evidence && $unit->evidence->count() > 0)
                    <div class="space-y-3">
                        @foreach($unit->evidence as $evidence)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3 flex-1">
                                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                                            @php
                                                $iconMap = [
                                                    'document' => 'description',
                                                    'certificate' => 'workspace_premium',
                                                    'work_sample' => 'work',
                                                    'project' => 'folder',
                                                    'photo' => 'image',
                                                    'video' => 'videocam',
                                                    'portfolio' => 'folder_open',
                                                ];
                                            @endphp
                                            <span class="material-symbols-outlined text-gray-600">{{ $iconMap[$evidence->evidence_type] ?? 'attach_file' }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-semibold text-gray-900">{{ $evidence->evidence_number ?? 'EVD-' . $evidence->id }}</span>
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ ucfirst(str_replace('_', ' ', $evidence->evidence_type)) }}</span>
                                            </div>
                                            <p class="text-sm text-gray-900 font-medium mb-1 truncate">{{ $evidence->title }}</p>
                                            @if($evidence->description)
                                                <p class="text-xs text-gray-600 truncate">{{ $evidence->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                                <span>{{ $evidence->file_name }}</span>
                                                @if($evidence->file_size)
                                                    <span>{{ number_format($evidence->file_size / 1024, 1) }} KB</span>
                                                @endif
                                            </div>
                                            <!-- Verification Status -->
                                            @php
                                                $verifyColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'verified' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                                $verifyLabels = [
                                                    'pending' => 'Menunggu Verifikasi',
                                                    'verified' => 'Terverifikasi',
                                                    'rejected' => 'Ditolak',
                                                ];
                                            @endphp
                                            <span class="inline-flex mt-2 px-2 py-0.5 rounded text-xs font-medium {{ $verifyColors[$evidence->verification_status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $verifyLabels[$evidence->verification_status] ?? ucfirst($evidence->verification_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        @if($evidence->file_path)
                                            <a href="{{ Storage::url($evidence->file_path) }}" target="_blank"
                                                class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                                <span class="material-symbols-outlined">visibility</span>
                                            </a>
                                        @endif
                                        @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                                            <form action="{{ route('admin.my-apl02.delete-evidence', [$unit, $evidence]) }}" method="POST"
                                                onsubmit="return confirm('Hapus bukti ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">upload_file</span>
                        <p class="text-sm">Belum ada bukti yang diupload</p>
                        @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                            <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                Upload Bukti Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Assessment Reviews -->
            @if($unit->assessorReviews && $unit->assessorReviews->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Catatan Review Asesor</h3>

                    <div class="space-y-3">
                        @foreach($unit->assessorReviews->sortByDesc('created_at') as $review)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-sm">rate_review</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-semibold text-gray-900">{{ $review->review_number ?? 'Review' }}</span>
                                            @php
                                                $decisionColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'competent' => 'bg-green-100 text-green-800',
                                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                                    'requires_clarification' => 'bg-orange-100 text-orange-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                                            </span>
                                        </div>
                                        @if($review->overall_comments)
                                            <p class="text-sm text-gray-700 mt-2">{{ $review->overall_comments }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">{{ $review->created_at?->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Unit Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $unit->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($unit->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Disubmit</p>
                                <p class="text-xs text-gray-500">{{ $unit->submitted_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->assigned_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">assignment</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Ditugaskan ke Asesor</p>
                                <p class="text-xs text-gray-500">{{ $unit->assigned_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">play_arrow</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Asesmen Dimulai</p>
                                <p class="text-xs text-gray-500">{{ $unit->started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Selesai</p>
                                <p class="text-xs text-gray-500">{{ $unit->completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>

                <div class="space-y-3">
                    @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                        <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                            class="flex items-center justify-center gap-2 w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all">
                            <span class="material-symbols-outlined">upload</span>
                            Upload Bukti
                        </a>

                        @if($unit->evidence && $unit->evidence->count() > 0)
                            <form action="{{ route('admin.my-apl02.submit', $unit) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin submit unit ini untuk direview?')"
                                    class="flex items-center justify-center gap-2 w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all">
                                    <span class="material-symbols-outlined">send</span>
                                    Submit untuk Review
                                </button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('admin.my-apl02.index') }}"
                        class="flex items-center justify-center gap-2 w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Dibuat:</span><br>
                        {{ $unit->created_at->format('d M Y H:i') }}
                    </p>
                    @if($unit->updated_at != $unit->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Terakhir Diperbarui:</span><br>
                            {{ $unit->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Info Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Terkunci</span>
                        @if($unit->is_locked)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Ya</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Tidak</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Disubmit</span>
                        @if($unit->is_submitted)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Ya</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Belum</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Selesai</span>
                        @if($unit->is_completed)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">Ya</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Belum</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Kompeten</span>
                        @if($unit->is_competent)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Ya</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Belum</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Scheme Unit Info -->
            @if($unit->schemeUnit)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Info Unit Kompetensi</h3>

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Kode Unit</p>
                            <p class="text-sm font-mono font-semibold text-gray-900">{{ $unit->schemeUnit->code }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Judul</p>
                            <p class="text-sm font-medium text-gray-900">{{ $unit->schemeUnit->title ?? $unit->schemeUnit->name }}</p>
                        </div>
                        @if($unit->schemeUnit->elements && $unit->schemeUnit->elements->count() > 0)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Elemen Kompetensi</p>
                                <ul class="text-xs text-gray-700 space-y-1">
                                    @foreach($unit->schemeUnit->elements->take(5) as $element)
                                        <li class="flex items-start gap-2">
                                            <span class="material-symbols-outlined text-sm text-gray-400">check</span>
                                            <span>{{ $element->code }} - {{ Str::limit($element->title ?? $element->name, 40) }}</span>
                                        </li>
                                    @endforeach
                                    @if($unit->schemeUnit->elements->count() > 5)
                                        <li class="text-gray-500 ml-5">... dan {{ $unit->schemeUnit->elements->count() - 5 }} elemen lainnya</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
