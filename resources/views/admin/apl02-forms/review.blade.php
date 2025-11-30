@extends('layouts.admin')

@section('title', 'Detail APL-02 Form')

@php
    $active = 'apl02-form-reviews';
@endphp

@section('page_title', 'Detail APL-02 Form')
@section('page_description', 'Form: ' . $apl02Form->form_number)

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

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.apl02-form-reviews.my-reviews') }}" class="flex items-center text-gray-600 hover:text-gray-900">
            <span class="material-symbols-outlined mr-1">arrow_back</span>
            Kembali
        </a>
        @if(in_array($apl02Form->status, ['submitted', 'under_review']))
            <div class="flex gap-2">
                @if($apl02Form->status === 'submitted')
                    <form action="{{ route('admin.apl02-form-reviews.start-review', $apl02Form) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">play_arrow</span>
                            Mulai Review
                        </button>
                    </form>
                @else
                    <a href="{{ route('admin.apl02-form-reviews.review', $apl02Form) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">rate_review</span>
                        Lanjut Review
                    </a>
                @endif
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Form</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">No. Form</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->form_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-yellow-100 text-yellow-800',
                                'under_review' => 'bg-blue-100 text-blue-800',
                                'revision_required' => 'bg-orange-100 text-orange-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'under_review' => 'Sedang Direview',
                                'revision_required' => 'Perlu Revisi',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$apl02Form->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$apl02Form->status] ?? $apl02Form->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Skema</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->event->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Assessee Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Asesi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. APL-01</p>
                        <a href="{{ route('admin.apl01.show', $apl02Form->apl01Form) }}" class="font-semibold text-blue-600 hover:underline">
                            {{ $apl02Form->apl01Form->form_number ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Instansi</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->institution ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Self Assessment Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Asesmen Mandiri</h3>
                @php
                    $selfAssessment = $apl02Form->self_assessment ?? [];
                    $totalUnits = count($selfAssessment);
                    $completedUnits = collect($selfAssessment)->filter(fn($u) => isset($u['is_competent']))->count();
                    $competentUnits = collect($selfAssessment)->filter(fn($u) => ($u['is_competent'] ?? false) === true)->count();
                @endphp
                <div class="grid grid-cols-4 gap-4 text-center">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUnits }}</p>
                        <p class="text-sm text-gray-500">Total Unit</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $completedUnits }}</p>
                        <p class="text-sm text-gray-500">Terisi</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $competentUnits }}</p>
                        <p class="text-sm text-gray-500">Kompeten</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">{{ $completedUnits - $competentUnits }}</p>
                        <p class="text-sm text-gray-500">Belum Kompeten</p>
                    </div>
                </div>
            </div>

            <!-- Review Result (if completed) -->
            @if(in_array($apl02Form->status, ['approved', 'rejected']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Hasil Review</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Keputusan</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $apl02Form->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $apl02Form->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                            </span>
                        </div>
                        @if($apl02Form->assessor_notes)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Catatan Assessor</p>
                                <p class="text-gray-900">{{ $apl02Form->assessor_notes }}</p>
                            </div>
                        @endif
                        @if($apl02Form->assessor_feedback)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Feedback</p>
                                <p class="text-gray-900">{{ $apl02Form->assessor_feedback }}</p>
                            </div>
                        @endif
                        @if($apl02Form->review_completed_at)
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Review Selesai</p>
                                <p class="text-gray-900">{{ $apl02Form->review_completed_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Progress Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Progress</h3>
                <div class="flex items-center justify-center mb-4">
                    <div class="relative w-32 h-32">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#3b82f6" stroke-width="3" stroke-dasharray="{{ $apl02Form->completion_percentage }}, 100"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $apl02Form->completion_percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600 text-sm">edit</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $apl02Form->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($apl02Form->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-yellow-600 text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->submitted_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($apl02Form->review_started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 text-sm">rate_review</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Review Dimulai</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($apl02Form->review_completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full {{ $apl02Form->status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                <span class="material-symbols-outlined {{ $apl02Form->status === 'approved' ? 'text-green-600' : 'text-red-600' }} text-sm">{{ $apl02Form->status === 'approved' ? 'check_circle' : 'cancel' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $apl02Form->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
