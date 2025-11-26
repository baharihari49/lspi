@extends('layouts.admin')

@section('title', 'Certification Flow Dashboard')

@php
    $active = 'certification-flow';
@endphp

@section('page_title', 'Certification Flow Dashboard')
@section('page_description', 'Monitor and manage the certification process from APL-01 to certificate issuance')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600">description</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">APL-01 Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $apl01Forms->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600">assignment</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">APL-02 In Progress</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ collect($flowStatuses)->filter(fn($s) => in_array($s['current_stage'], ['apl02_generated', 'apl02_in_progress']))->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600">event</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Assessment Scheduled</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ collect($flowStatuses)->filter(fn($s) => $s['assessment']['exists'] && $s['assessment']['status'] === 'scheduled')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600">workspace_premium</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Certificate Issued</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ collect($flowStatuses)->filter(fn($s) => $s['certificate']['exists'])->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('admin.certification-flow.dashboard') }}" method="GET" class="space-y-4">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           placeholder="Form number, name...">
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Flow Status</label>
                    <select name="flow_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('flow_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="apl02_generated" {{ request('flow_status') === 'apl02_generated' ? 'selected' : '' }}>APL-02 Generated</option>
                        <option value="apl02_completed" {{ request('flow_status') === 'apl02_completed' ? 'selected' : '' }}>APL-02 Completed</option>
                        <option value="assessment_scheduled" {{ request('flow_status') === 'assessment_scheduled' ? 'selected' : '' }}>Assessment Scheduled</option>
                        <option value="assessment_completed" {{ request('flow_status') === 'assessment_completed' ? 'selected' : '' }}>Assessment Completed</option>
                        <option value="certificate_issued" {{ request('flow_status') === 'certificate_issued' ? 'selected' : '' }}>Certificate Issued</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.certification-flow.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Flow Status Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">APL-01</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Flow Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Stage</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($apl01Forms as $apl01)
                    @php $flow = $flowStatuses[$apl01->id] ?? null; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-blue-900 text-2xl">description</span>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $apl01->form_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $apl01->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $apl01->assessee->full_name ?? $apl01->full_name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $apl01->scheme->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($flow)
                            <div class="flex items-center justify-center gap-1">
                                <!-- APL-01 Approved -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $flow['apl01']['approved'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">APL-01</span>
                                </div>
                                <div class="w-4 h-0.5 {{ $flow['apl02']['generated'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                <!-- APL-02 -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $flow['apl02']['all_complete'] ? 'bg-green-500 text-white' : ($flow['apl02']['generated'] ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                                        <span class="material-symbols-outlined text-sm">{{ $flow['apl02']['all_complete'] ? 'check' : ($flow['apl02']['generated'] ? 'pending' : 'remove') }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">APL-02</span>
                                </div>
                                <div class="w-4 h-0.5 {{ $flow['assessment']['exists'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                <!-- Assessment -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $flow['assessment']['approved'] ? 'bg-green-500 text-white' : ($flow['assessment']['exists'] ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                                        <span class="material-symbols-outlined text-sm">{{ $flow['assessment']['approved'] ? 'check' : ($flow['assessment']['exists'] ? 'pending' : 'remove') }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">Asesmen</span>
                                </div>
                                <div class="w-4 h-0.5 {{ $flow['certificate']['exists'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                <!-- Certificate -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $flow['certificate']['exists'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                        <span class="material-symbols-outlined text-sm">{{ $flow['certificate']['exists'] ? 'check' : 'remove' }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">Sertifikat</span>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($flow)
                            @php
                                $stageColors = [
                                    'apl01_pending' => 'bg-gray-100 text-gray-700',
                                    'apl01_approved' => 'bg-blue-100 text-blue-700',
                                    'apl02_generated' => 'bg-yellow-100 text-yellow-700',
                                    'apl02_in_progress' => 'bg-yellow-100 text-yellow-700',
                                    'apl02_completed' => 'bg-purple-100 text-purple-700',
                                    'assessment_scheduled' => 'bg-indigo-100 text-indigo-700',
                                    'assessment_in_progress' => 'bg-indigo-100 text-indigo-700',
                                    'assessment_completed' => 'bg-purple-100 text-purple-700',
                                    'assessment_approved' => 'bg-green-100 text-green-700',
                                    'certificate_issued' => 'bg-green-100 text-green-700',
                                ];
                                $stageLabels = [
                                    'apl01_pending' => 'Menunggu',
                                    'apl01_approved' => 'APL-01 Disetujui',
                                    'apl02_generated' => 'APL-02 Dibuat',
                                    'apl02_in_progress' => 'APL-02 Dalam Proses',
                                    'apl02_completed' => 'APL-02 Selesai',
                                    'assessment_scheduled' => 'Asesmen Terjadwal',
                                    'assessment_in_progress' => 'Asesmen Berlangsung',
                                    'assessment_completed' => 'Asesmen Selesai',
                                    'assessment_approved' => 'Asesmen Disetujui',
                                    'certificate_issued' => 'Sertifikat Terbit',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $stageColors[$flow['current_stage']] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $stageLabels[$flow['current_stage']] ?? ucfirst(str_replace('_', ' ', $flow['current_stage'])) }}
                            </span>
                            @if($flow['apl02']['total_units'] > 0)
                            <p class="text-xs text-gray-500 mt-1">
                                Unit: {{ $flow['apl02']['competent_units'] }}/{{ $flow['apl02']['total_units'] }} Kompeten
                            </p>
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.certification-flow.show', $apl01) }}"
                               class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition inline-flex" title="View Details">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">conversion_path</span>
                                <p class="text-gray-500 font-medium text-lg">No certification flows found</p>
                                <p class="text-gray-400 text-sm mt-1">Approved APL-01 forms will appear here to track their certification progress</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($apl01Forms->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $apl01Forms->links() }}
            </div>
        @endif
    </div>
@endsection
