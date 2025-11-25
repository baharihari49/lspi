@extends('layouts.admin')

@section('title', 'APL-01 Saya')

@php $active = 'my-apl01'; @endphp

@section('page_title', 'APL-01 Saya')
@section('page_description', 'Daftar formulir APL-01 (Permohonan Sertifikasi Kompetensi)')

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

<div class="space-y-6">
    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">description</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total</p>
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
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">pending</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Submitted</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['submitted'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Disetujui</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">cancel</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Ditolak</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-2">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor form..."
                    class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">search</span>
                Cari
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.my-apl01.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Forms List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($forms->isEmpty())
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-gray-300 text-6xl">description</span>
                <p class="mt-4 text-gray-500">Belum ada formulir APL-01</p>
                <p class="text-sm text-gray-400">Formulir akan muncul setelah Anda mendaftar ke event sertifikasi</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Form</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-medium text-blue-600">{{ $form->form_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $form->scheme?->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $form->scheme?->code ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $form->event?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $form->created_at?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'submitted' => 'bg-yellow-100 text-yellow-800',
                                            'under_review' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'revised' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Draft',
                                            'submitted' => 'Submitted',
                                            'under_review' => 'Under Review',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'revised' => 'Perlu Revisi',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$form->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$form->status] ?? $form->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.my-apl01.show', $form) }}"
                                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </a>
                                        @if(in_array($form->status, ['draft', 'revised']))
                                            <a href="{{ route('admin.my-apl01.edit', $form) }}"
                                                class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined text-xl">edit</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($forms->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $forms->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
