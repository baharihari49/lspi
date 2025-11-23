@extends('layouts.admin')

@section('title', 'Assessment Criteria')

@php
    $active = 'assessment-criteria';
@endphp

@section('page_title', 'Assessment Criteria')
@section('page_description', 'Manage individual assessment criteria and KUK')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assessment-criteria.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search element code or title..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="assessment_unit_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Units</option>
                    @foreach(\App\Models\AssessmentUnit::with('assessment.assessee')->orderBy('created_at', 'desc')->limit(50)->get() as $unit)
                        <option value="{{ $unit->id }}" {{ request('assessment_unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit_code }} - {{ $unit->assessment->assessee->full_name }}
                        </option>
                    @endforeach
                </select>

                <select name="result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Results</option>
                    <option value="pending" {{ request('result') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="competent" {{ request('result') === 'competent' ? 'selected' : '' }}>Competent</option>
                    <option value="not_yet_competent" {{ request('result') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                </select>

                <select name="is_critical" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Criteria</option>
                    <option value="true" {{ request('is_critical') === 'true' ? 'selected' : '' }}>Critical Only</option>
                    <option value="false" {{ request('is_critical') === 'false' ? 'selected' : '' }}>Non-Critical Only</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.assessment-criteria.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Criteria List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Element (KUK)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit & Assessee</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Critical</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($criteria as $criterion)
                        <tr class="hover:bg-gray-50 transition {{ $criterion->is_critical ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">{{ $criterion->is_critical ? 'star' : 'checklist' }}</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $criterion->element_code }}</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($criterion->element_title, 80) }}</p>
                                        @if($criterion->assessed_at)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="font-medium">Assessed:</span> {{ $criterion->assessed_at->format('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $criterion->assessmentUnit->unit_code }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($criterion->assessmentUnit->unit_title, 40) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $criterion->assessmentUnit->assessment->assessee->full_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    {{ ucwords(str_replace('_', ' ', $criterion->assessment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                    ];
                                    $resultColor = $resultColors[$criterion->result] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                    {{ ucwords(str_replace('_', ' ', $criterion->result)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($criterion->is_critical)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        <span class="material-symbols-outlined text-sm mr-1">priority_high</span>
                                        Critical
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-criteria.show', $criterion) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessment-criteria.edit', $criterion) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">checklist</span>
                                    <p class="text-gray-500 font-medium text-lg">No assessment criteria found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or assess some units</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($criteria->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $criteria->links() }}
            </div>
        @endif
    </div>
@endsection
