@extends('layouts.admin')

@section('title', 'Detail APL-02 - ' . $apl02Form->form_number)

@php $active = 'my-apl02-forms'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-apl02-forms.index') }}" class="hover:text-blue-600">APL-02 Saya</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-900">{{ $apl02Form->form_number }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">{{ $apl02Form->form_number }}</h1>
            <p class="text-gray-600 mt-1">{{ $apl02Form->scheme->name ?? '-' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($apl02Form->is_editable)
                <a href="{{ route('admin.my-apl02-forms.edit', $apl02Form) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    <span>Edit Form</span>
                </a>
            @endif
            @if($apl02Form->is_submittable)
                <form action="{{ route('admin.my-apl02-forms.submit', $apl02Form) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined text-lg">send</span>
                        <span>Submit</span>
                    </button>
                </form>
            @endif
            @if($apl02Form->status === 'revision_required')
                <form action="{{ route('admin.my-apl02-forms.resubmit', $apl02Form) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined text-lg">redo</span>
                        <span>Submit Ulang</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Form</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-600">Form Number</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->form_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                'revision_required' => 'bg-orange-100 text-orange-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$apl02Form->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $apl02Form->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Skema</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Event</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->event->name ?? '-' }}</p>
                    </div>
                    @if($apl02Form->apl01Form)
                        <div>
                            <p class="text-xs text-gray-600">APL-01</p>
                            <a href="{{ route('admin.my-apl01.show', $apl02Form->apl01Form) }}" class="font-semibold text-blue-600 hover:underline">
                                {{ $apl02Form->apl01Form->form_number }}
                            </a>
                        </div>
                    @endif
                    @if($apl02Form->assessor)
                        <div>
                            <p class="text-xs text-gray-600">Assessor</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->assessor->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Progress Pengisian</h3>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Kelengkapan Form</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $apl02Form->completion_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $apl02Form->completion_percentage }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-blue-900">{{ $apl02Form->scheme->units->count() ?? 0 }}</p>
                        <p class="text-xs text-blue-700">Total Unit</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg text-center">
                        <p class="text-2xl font-bold text-purple-900">{{ count($apl02Form->evidence_files ?? []) }}</p>
                        <p class="text-xs text-purple-700">File Bukti</p>
                    </div>
                </div>
            </div>

            <!-- Self Assessment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Asesmen Mandiri</h3>

                @if($apl02Form->self_assessment && count($apl02Form->self_assessment) > 0)
                    <div class="space-y-4">
                        @foreach($apl02Form->self_assessment as $index => $assessment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Unit {{ $index + 1 }}</p>
                                        <p class="text-sm font-mono text-gray-600">{{ $assessment['unit_code'] ?? '-' }}</p>
                                        <p class="font-semibold text-gray-900">{{ $assessment['unit_title'] ?? '-' }}</p>
                                    </div>
                                    @if(isset($assessment['is_competent']))
                                        @if($assessment['is_competent'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                                Kompeten
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">
                                                Belum Kompeten
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">
                                            Belum Diisi
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($assessment['evidence_description']))
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-500 mb-1">Deskripsi Bukti Kompetensi:</p>
                                        <p class="text-sm text-gray-700">{{ $assessment['evidence_description'] }}</p>
                                    </div>
                                @endif

                                @if(!empty($assessment['notes']))
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500 mb-1">Catatan:</p>
                                        <p class="text-sm text-gray-700">{{ $assessment['notes'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">assignment</span>
                        <p class="text-sm">Asesmen mandiri belum diisi</p>
                        @if($apl02Form->is_editable)
                            <a href="{{ route('admin.my-apl02-forms.edit', $apl02Form) }}" class="mt-3 inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Isi Sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Portfolio Summary -->
            @if($apl02Form->portfolio_summary)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Portfolio</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($apl02Form->portfolio_summary)) !!}
                    </div>
                </div>
            @endif

            <!-- Evidence Files -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">File Bukti</h3>

                @if($apl02Form->evidence_files && count($apl02Form->evidence_files) > 0)
                    <div class="space-y-3">
                        @foreach($apl02Form->evidence_files as $index => $file)
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-gray-400">attach_file</span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $file['name'] ?? 'File ' . ($index + 1) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : '' }}
                                            @if(isset($file['uploaded_at']))
                                                <span class="mx-1">•</span>
                                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('d M Y H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if(isset($file['path']))
                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Download">
                                        <span class="material-symbols-outlined">download</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">folder_open</span>
                        <p class="text-sm">Belum ada file bukti yang diupload</p>
                        @if($apl02Form->is_editable)
                            <a href="{{ route('admin.my-apl02-forms.edit', $apl02Form) }}" class="mt-3 inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                <span class="material-symbols-outlined text-sm">upload</span>
                                Upload File
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Assessor Feedback -->
            @if($apl02Form->assessor_feedback || $apl02Form->recommendations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Feedback dari Assessor</h3>

                    @if($apl02Form->assessor_feedback)
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700 font-semibold mb-1">Feedback:</p>
                            <p class="text-sm text-blue-900 whitespace-pre-wrap">{{ $apl02Form->assessor_feedback }}</p>
                        </div>
                    @endif

                    @if($apl02Form->recommendations)
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-green-700 font-semibold mb-1">Rekomendasi:</p>
                            <p class="text-sm text-green-900 whitespace-pre-wrap">{{ $apl02Form->recommendations }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Hasil Asesmen</span>
                        @php
                            $resultColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'competent' => 'bg-green-100 text-green-800',
                                'not_yet_competent' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $resultColors[$apl02Form->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $apl02Form->assessment_result_label }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Deklarasi</span>
                        @if($apl02Form->declaration_agreed)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Disetujui</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Belum</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Revision Notes -->
            @if($apl02Form->status === 'revision_required' && $apl02Form->revision_notes)
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">warning</span>
                        Perlu Revisi
                    </h3>

                    <ul class="list-disc list-inside space-y-2 text-sm text-orange-800">
                        @foreach($apl02Form->revision_notes as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('admin.my-apl02-forms.edit', $apl02Form) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">edit</span>
                            Edit & Perbaiki
                        </a>
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
                            <p class="text-sm font-semibold text-gray-900">Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $apl02Form->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($apl02Form->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Disubmit</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->submitted_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->review_started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">rate_review</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Review Dimulai</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->review_completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Review Selesai</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('admin.my-apl02-forms.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
