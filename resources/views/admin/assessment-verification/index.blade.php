@extends('layouts.admin')

@section('title', 'Assessment Verification')

@php
    $active = 'assessment-verification';
@endphp

@section('page_title', 'Assessment Verification')
@section('page_description', 'Quality assurance and verification of assessments')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.assessment-verification.create') }}" class="h-12 px-6 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            New Verification
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assessment-verification.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search verification number..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="assessment_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Assessments</option>
                    @foreach(\App\Models\Assessment::with('assessee')->orderBy('created_at', 'desc')->limit(50)->get() as $assessment)
                        <option value="{{ $assessment->id }}" {{ request('assessment_id') == $assessment->id ? 'selected' : '' }}>
                            {{ $assessment->assessment_number }} - {{ $assessment->assessee->full_name }}
                        </option>
                    @endforeach
                </select>

                <select name="verifier_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Verifiers</option>
                    @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name', 'assessor'); })->orderBy('name')->get() as $verifier)
                        <option value="{{ $verifier->id }}" {{ request('verifier_id') == $verifier->id ? 'selected' : '' }}>
                            {{ $verifier->name }}
                        </option>
                    @endforeach
                </select>

                <select name="verification_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('verification_status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('verification_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="approved" {{ request('verification_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="requires_modification" {{ request('verification_status') === 'requires_modification' ? 'selected' : '' }}>Requires Modification</option>
                    <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <select name="verification_level" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Levels</option>
                    <option value="unit_level" {{ request('verification_level') === 'unit_level' ? 'selected' : '' }}>Unit Level</option>
                    <option value="assessment_level" {{ request('verification_level') === 'assessment_level' ? 'selected' : '' }}>Assessment Level</option>
                    <option value="quality_assurance" {{ request('verification_level') === 'quality_assurance' ? 'selected' : '' }}>Quality Assurance</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.assessment-verification.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Verifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verification</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verifier</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($verifications as $ver)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">verified</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $ver->verification_number }}</p>
                                        <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $ver->verification_type)) }}</p>
                                        @if($ver->verified_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $ver->verified_at->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $ver->assessment->assessment_number }}</p>
                                <p class="text-sm text-gray-600">{{ $ver->assessment->assessee->full_name }}</p>
                                @if($ver->assessmentUnit)
                                    <p class="text-xs text-gray-500 mt-1">Unit: {{ $ver->assessmentUnit->unit_code }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $ver->verifier->name }}</p>
                                <p class="text-sm text-gray-600">Verifier</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    {{ ucwords(str_replace('_', ' ', $ver->verification_level)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'requires_modification' => 'bg-orange-100 text-orange-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusColor = $statusColors[$ver->verification_status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucwords(str_replace('_', ' ', $ver->verification_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($ver->verification_result)
                                    @php
                                        $resultColors = [
                                            'satisfactory' => 'bg-green-100 text-green-700',
                                            'needs_minor_changes' => 'bg-yellow-100 text-yellow-700',
                                            'needs_major_changes' => 'bg-orange-100 text-orange-700',
                                            'unsatisfactory' => 'bg-red-100 text-red-700',
                                        ];
                                        $resultColor = $resultColors[$ver->verification_result] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                        {{ ucwords(str_replace('_', ' ', $ver->verification_result)) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-verification.show', $ver) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessment-verification.edit', $ver) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">verified</span>
                                    <p class="text-gray-500 font-medium text-lg">No verifications found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new verification</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($verifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $verifications->links() }}
            </div>
        @endif
    </div>
@endsection
