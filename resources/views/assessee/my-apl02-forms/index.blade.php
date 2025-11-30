@extends('layouts.admin')

@section('title', 'APL-02 Saya')

@php $active = 'my-apl02-forms'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">APL-02 Saya</h1>
            <p class="text-gray-600 mt-1">Formulir asesmen mandiri per skema sertifikasi</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">assignment</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Form</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <span class="material-symbols-outlined text-gray-600">edit_note</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Draft</p>
                    <p class="text-xl font-bold text-gray-600">{{ $stats['draft'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">send</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Submitted</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['submitted'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">verified</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Approved</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
                </div>
            </div>
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

    <!-- Forms List -->
    @if($forms->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <span class="material-symbols-outlined text-gray-300 text-6xl">assignment</span>
            <p class="mt-4 text-gray-500">Belum ada APL-02</p>
            <p class="text-sm text-gray-400">Formulir APL-02 akan muncul setelah APL-01 Anda disetujui</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($forms as $form)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 text-xs font-mono bg-blue-100 text-blue-800 rounded">{{ $form->form_number }}</span>
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
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$form->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $form->status_label }}
                                    </span>
                                </div>

                                <h4 class="font-semibold text-gray-900">{{ $form->scheme->name ?? '-' }}</h4>

                                <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    @if($form->event)
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">event</span>
                                            {{ $form->event->name }}
                                        </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                                        {{ $form->created_at->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-600">Progress</span>
                                        <span class="text-xs font-semibold text-gray-900">{{ $form->completion_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $form->completion_percentage }}%"></div>
                                    </div>
                                </div>

                                @if($form->status === 'revision_required' && $form->revision_notes)
                                    <div class="mt-3 p-3 bg-orange-50 rounded-lg">
                                        <p class="text-xs text-orange-800 font-semibold mb-1">Perlu Revisi:</p>
                                        <ul class="list-disc list-inside text-xs text-orange-700">
                                            @foreach($form->revision_notes as $note)
                                                <li>{{ $note }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('admin.my-apl02-forms.show', $form) }}"
                                    class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                                @if($form->is_editable)
                                    <a href="{{ route('admin.my-apl02-forms.edit', $form) }}"
                                        class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($forms->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $forms->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
