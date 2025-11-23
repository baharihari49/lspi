@extends('layouts.admin')

@section('title', 'Assessment Units')

@php
    $active = 'assessment-units';
@endphp

@section('page_title', 'Assessment Units')
@section('page_description', 'Manage competency unit assessments')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assessment-units.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search unit code or title..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="assessment_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Assessments</option>
                    @foreach(\App\Models\Assessment::with('assessee')->orderBy('created_at', 'desc')->limit(50)->get() as $assessment)
                        <option value="{{ $assessment->id }}" {{ request('assessment_id') == $assessment->id ? 'selected' : '' }}>
                            {{ $assessment->assessment_number }} - {{ $assessment->assessee->full_name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>

                <select name="result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Results</option>
                    <option value="pending" {{ request('result') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="competent" {{ request('result') === 'competent' ? 'selected' : '' }}>Competent</option>
                    <option value="not_yet_competent" {{ request('result') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.assessment-units.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Units List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">view_module</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $unit->unit_code }}</p>
                                        <p class="text-sm text-gray-600">{{ $unit->unit_title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Method:</span> {{ ucwords(str_replace('_', ' ', $unit->assessment_method)) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $unit->assessment->assessment_number }}</p>
                                <p class="text-sm text-gray-600">{{ $unit->assessment->assessee->full_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $unit->assessor ? $unit->assessor->name : '-' }}</p>
                                @if($unit->assessor)
                                    <p class="text-sm text-gray-600">Assessor</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">Not assigned</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                    ];
                                    $statusColor = $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucwords(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                    ];
                                    $resultColor = $resultColors[$unit->result] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                    {{ ucwords(str_replace('_', ' ', $unit->result)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($unit->score)
                                    <p class="text-2xl font-bold text-blue-900">{{ number_format($unit->score, 1) }}%</p>
                                @else
                                    <p class="text-sm text-gray-400">-</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-units.show', $unit) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessment-units.edit', $unit) }}"
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
                                    <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">view_module</span>
                                    <p class="text-gray-500 font-medium text-lg">No assessment units found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new assessment</p>
                                </div>
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
