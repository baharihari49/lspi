@extends('layouts.admin')

@section('title', 'Assessment Management')

@php
    $active = 'assessments';
@endphp

@section('page_title', 'Assessment Management')
@section('page_description', 'Manage competency assessments and their progress')

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
                    <h2 class="text-lg font-bold text-gray-900">Assessments List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $assessments->total() }} assessments</p>
                </div>
                <a href="{{ route('admin.assessments.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Create Assessment</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.assessments.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by assessment number, title, or assessee name..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'assessee_id', 'scheme_id', 'date_from', 'date_to']))
                        <a href="{{ route('admin.assessments.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-4 gap-2">
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>

                    <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">

                    <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assessments as $assessment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">assignment</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $assessment->assessment_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $assessment->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Lead Assessor:</span> {{ $assessment->leadAssessor->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $assessment->assessee->full_name ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $assessment->assessee->assessee_number ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $assessment->scheme->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $assessment->scheme->code ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $assessment->scheduled_date ? $assessment->scheduled_date->format('d M Y') : '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $assessment->scheduled_time ? $assessment->scheduled_time->format('H:i') : '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $assessment->venue ?? 'No venue' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-purple-100 text-purple-700',
                                        'under_review' => 'bg-orange-100 text-orange-700',
                                        'verified' => 'bg-indigo-100 text-indigo-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusColor = $statusColors[$assessment->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucwords(str_replace('_', ' ', $assessment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                    ];
                                    $resultColor = $resultColors[$assessment->overall_result] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                    {{ ucwords(str_replace('_', ' ', $assessment->overall_result)) }}
                                </span>
                                @if($assessment->overall_score)
                                    <p class="text-xs text-gray-500 mt-1">Score: {{ number_format($assessment->overall_score, 1) }}%</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessments.show', $assessment) }}" class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    @if($assessment->canBeModified())
                                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                    @endif
                                    @if($assessment->status === 'draft')
                                        <form action="{{ route('admin.assessments.destroy', $assessment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">assignment</span>
                                <p class="text-gray-500 font-medium">No assessments found</p>
                                <p class="text-sm text-gray-400 mt-2">Create your first assessment to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assessments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assessments->links() }}
            </div>
        @endif
    </div>
@endsection
