@extends('layouts.admin')

@section('title', 'Assessment Observations')

@php
    $active = 'assessment-observations';
@endphp

@section('page_title', 'Assessment Observations')
@section('page_description', 'Track observation-based assessments')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.assessment-observations.create') }}" class="h-12 px-6 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            New Observation
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assessment-observations.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search observation number or activity..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="assessment_unit_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Units</option>
                    @foreach(\App\Models\AssessmentUnit::with('assessment.assessee')->orderBy('created_at', 'desc')->limit(50)->get() as $unit)
                        <option value="{{ $unit->id }}" {{ request('assessment_unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit_code }} - {{ $unit->assessment->assessee->full_name }}
                        </option>
                    @endforeach
                </select>

                <select name="observer_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Observers</option>
                    @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name', 'assessor'); })->orderBy('name')->get() as $observer)
                        <option value="{{ $observer->id }}" {{ request('observer_id') == $observer->id ? 'selected' : '' }}>
                            {{ $observer->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.assessment-observations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Observations List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Observation</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit & Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Observer</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Duration</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($observations as $obs)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">visibility</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $obs->observation_number }}</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($obs->activity_observed, 60) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $obs->assessmentUnit->unit_code }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($obs->assessmentUnit->unit_title, 40) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $obs->assessmentUnit->assessment->assessee->full_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $obs->observer->name ?? '-' }}</p>
                                @if($obs->observer)
                                    <p class="text-sm text-gray-600">Observer</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">Not assigned</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="font-medium text-gray-900">{{ $obs->observed_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $obs->duration_minutes }} minutes</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-observations.show', $obs) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessment-observations.edit', $obs) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">visibility</span>
                                    <p class="text-gray-500 font-medium text-lg">No observations found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new observation</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($observations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $observations->links() }}
            </div>
        @endif
    </div>
@endsection
