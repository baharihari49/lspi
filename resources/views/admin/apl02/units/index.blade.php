@extends('layouts.admin')

@section('title', 'APL-02 Portfolio Units')

@php
    $active = 'apl02-units';
@endphp

@section('page_title', 'APL-02 Portfolio Units')
@section('page_description', 'Manage APL-02 portfolio unit assessments')

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
                    <h2 class="text-lg font-bold text-gray-900">Portfolio Units</h2>
                    <p class="text-sm text-gray-600">Total: {{ $units->total() }} units</p>
                </div>
                <a href="{{ route('admin.apl02.units.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Create Unit</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.apl02.units.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by unit code or title..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'assessment_result', 'scheme_id', 'assessor_id', 'event_id']))
                        <a href="{{ route('admin.apl02.units.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Status</option>
                        <option value="not_started" {{ request('status') === 'not_started' ? 'selected' : '' }}>Not Started</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="competent" {{ request('status') === 'competent' ? 'selected' : '' }}>Competent</option>
                        <option value="not_yet_competent" {{ request('status') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>

                    <select name="assessment_result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Results</option>
                        <option value="pending" {{ request('assessment_result') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="competent" {{ request('assessment_result') === 'competent' ? 'selected' : '' }}>Competent</option>
                        <option value="not_yet_competent" {{ request('assessment_result') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                        <option value="requires_more_evidence" {{ request('assessment_result') === 'requires_more_evidence' ? 'selected' : '' }}>Requires More Evidence</option>
                    </select>

                    <select name="scheme_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Schemes</option>
                        @foreach($schemes as $scheme)
                            <option value="{{ $scheme->id }}" {{ request('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                {{ $scheme->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="assessor_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Assessors</option>
                        @foreach($assessors as $assessor)
                            <option value="{{ $assessor->id }}" {{ request('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                {{ $assessor->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="event_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $unit->unit_code }}</div>
                                <div class="text-xs text-gray-600">{{ Str::limit($unit->unit_title, 40) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $unit->assessee->full_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $unit->scheme->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'not_started' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'submitted' => 'bg-yellow-100 text-yellow-800',
                                        'under_review' => 'bg-purple-100 text-purple-800',
                                        'competent' => 'bg-green-100 text-green-800',
                                        'not_yet_competent' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $unit->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'competent' => 'bg-green-100 text-green-800',
                                        'not_yet_competent' => 'bg-red-100 text-red-800',
                                        'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $resultColors[$unit->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $unit->assessment_result_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $unit->completion_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 font-semibold">{{ number_format($unit->completion_percentage, 0) }}%</span>
                                </div>
                                <div class="text-xs text-gray-600 mt-1">{{ $unit->total_evidence }} evidence</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $unit->assessor->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.apl02.units.show', $unit) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.apl02.units.edit', $unit) }}" class="text-gray-600 hover:text-gray-800" title="Edit">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('admin.apl02.units.destroy', $unit) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this unit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">inventory_2</span>
                                <p class="font-medium">No units found</p>
                                <p class="text-sm">Try adjusting your search or filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($units->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $units->links() }}
            </div>
        @endif
    </div>
@endsection
