@extends('layouts.admin')

@section('title', 'Review APL-02 Forms')

@php
    $active = 'apl02-form-reviews';
@endphp

@section('page_title', 'Review APL-02')
@section('page_description', 'Daftar APL-02 Form untuk direview')

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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Review APL-02 Saya</h2>
                    <p class="text-sm text-gray-600">Total: {{ $apl02Forms->total() }} form</p>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" action="{{ route('admin.apl02-form-reviews.my-reviews') }}" class="flex gap-2">
                <div class="flex gap-2 flex-1">
                    <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer transition {{ $status === 'pending' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                        <input type="radio" name="status" value="pending" {{ $status === 'pending' ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                        <span class="font-semibold {{ $status === 'pending' ? 'text-blue-900' : 'text-gray-700' }}">Pending</span>
                    </label>

                    <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer transition {{ $status === 'revision' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                        <input type="radio" name="status" value="revision" {{ $status === 'revision' ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                        <span class="font-semibold {{ $status === 'revision' ? 'text-blue-900' : 'text-gray-700' }}">Perlu Revisi</span>
                    </label>

                    <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer transition {{ $status === 'completed' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                        <input type="radio" name="status" value="completed" {{ $status === 'completed' ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                        <span class="font-semibold {{ $status === 'completed' ? 'text-blue-900' : 'text-gray-700' }}">Selesai</span>
                    </label>

                    <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer transition {{ $status === 'all' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                        <input type="radio" name="status" value="all" {{ $status === 'all' ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                        <span class="font-semibold {{ $status === 'all' ? 'text-blue-900' : 'text-gray-700' }}">Semua</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Form / Asesi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Submit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($apl02Forms as $form)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $form->form_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $form->apl01Form->assessee->full_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">APL-01: {{ $form->apl01Form->form_number ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $form->scheme->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $form->scheme->code ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $form->event->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-semibold text-gray-900">{{ $form->completion_percentage }}%</span>
                                    <div class="w-16 h-1.5 bg-gray-200 rounded-full mt-1">
                                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $form->completion_percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$form->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$form->status] ?? $form->status }}
                                    </span>
                                    @if($form->assessor_id === null && $form->status === 'submitted')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Belum di-assign
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($form->submitted_at)
                                    <p class="text-gray-900">{{ $form->submitted_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $form->submitted_at->format('H:i') }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.apl02-form-reviews.show', $form) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    @if($form->status === 'submitted' && $form->assessor_id === null)
                                        {{-- Unassigned form - show claim button --}}
                                        <form action="{{ route('admin.apl02-form-reviews.claim', $form) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Ambil untuk Review">
                                                <span class="material-symbols-outlined text-xl">person_add</span>
                                            </button>
                                        </form>
                                    @elseif(in_array($form->status, ['submitted', 'under_review']) && $form->assessor_id === auth()->id())
                                        {{-- Assigned to me --}}
                                        @if($form->status === 'submitted')
                                            <form action="{{ route('admin.apl02-form-reviews.start-review', $form) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Mulai Review">
                                                    <span class="material-symbols-outlined text-xl">play_arrow</span>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.apl02-form-reviews.review', $form) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Review">
                                                <span class="material-symbols-outlined text-xl">rate_review</span>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-3">assignment</span>
                                    <p class="text-gray-500 font-medium">Tidak ada APL-02 Form untuk direview</p>
                                    <p class="text-gray-400 text-sm mt-1">Form yang disubmit oleh asesi akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($apl02Forms->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $apl02Forms->links() }}
            </div>
        @endif
    </div>
@endsection
