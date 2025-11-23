@extends('layouts.admin')

@section('title', 'Assess Unit Criteria')

@php
    $active = 'assessment-criteria';
@endphp

@section('page_title', 'Assess Unit Criteria')
@section('page_description', $assessmentUnit->unit_code . ' - ' . $assessmentUnit->unit_title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Assessment Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                        <p class="text-gray-900">{{ $assessmentUnit->assessment->assessee->full_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment Number</label>
                        <a href="{{ route('admin.assessments.show', $assessmentUnit->assessment) }}" class="text-blue-900 hover:underline">
                            {{ $assessmentUnit->assessment->assessment_number }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Code</label>
                        <p class="text-gray-900">{{ $assessmentUnit->unit_code }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Title</label>
                        <p class="text-gray-900">{{ $assessmentUnit->unit_title }}</p>
                    </div>
                </div>
            </div>

            <!-- Criteria Assessment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Assessment Criteria (KUK)</h3>
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $assessmentUnit->criteria->where('result', 'competent')->count() }}</span> /
                        <span class="font-semibold">{{ $assessmentUnit->criteria->count() }}</span> Assessed
                    </div>
                </div>

                @if($assessmentUnit->criteria->count() > 0)
                    <!-- Bulk Actions -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('admin.assessment-criteria.bulk-assess') }}" method="POST" id="bulkAssessForm">
                            @csrf
                            <div class="flex flex-wrap items-end gap-4">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulk Assessment Result</label>
                                    <select name="result" id="bulkResult" required
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        <option value="">Select Result</option>
                                        <option value="competent">Competent</option>
                                        <option value="not_yet_competent">Not Yet Competent</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                                    <input type="text" name="notes" id="bulkNotes"
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                </div>
                                <button type="submit" class="h-12 px-6 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all whitespace-nowrap">
                                    Apply to Selected
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Individual Criteria -->
                    <div class="space-y-4">
                        @foreach($assessmentUnit->criteria as $criterion)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $criterion->is_critical ? 'bg-yellow-50 border-yellow-300' : '' }}">
                                <div class="flex items-start gap-4">
                                    <!-- Checkbox for Bulk Selection -->
                                    <div class="pt-1">
                                        <input type="checkbox" name="criteria_ids[]" value="{{ $criterion->id }}"
                                            form="bulkAssessForm"
                                            class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                    </div>

                                    <!-- Criterion Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-semibold text-gray-900">{{ $criterion->element_code }}</span>
                                            @if($criterion->is_critical)
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-yellow-200 text-yellow-800">
                                                    Critical
                                                </span>
                                            @endif
                                            @php
                                                $resultColors = [
                                                    'pending' => 'bg-gray-100 text-gray-800',
                                                    'competent' => 'bg-green-100 text-green-800',
                                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $resultColors[$criterion->result] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucwords(str_replace('_', ' ', $criterion->result)) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 mb-3">{{ $criterion->element_title }}</p>

                                        <!-- Individual Assessment Form -->
                                        <form action="{{ route('admin.assessment-criteria.assess', $criterion) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            @csrf
                                            <div>
                                                <select name="result" required
                                                    class="w-full h-10 px-3 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                    <option value="">Select Result</option>
                                                    <option value="competent" {{ $criterion->result === 'competent' ? 'selected' : '' }}>Competent</option>
                                                    <option value="not_yet_competent" {{ $criterion->result === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" name="evidence_collected" value="{{ $criterion->evidence_collected }}"
                                                    placeholder="Evidence collected"
                                                    class="w-full h-10 px-3 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                            </div>
                                            <div class="flex gap-2">
                                                <input type="text" name="notes" value="{{ $criterion->notes }}"
                                                    placeholder="Notes"
                                                    class="flex-1 h-10 px-3 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                <button type="submit" class="h-10 px-4 bg-blue-900 text-white text-sm font-semibold rounded-lg hover:bg-blue-800 transition-all whitespace-nowrap">
                                                    Assess
                                                </button>
                                            </div>
                                        </form>

                                        @if($criterion->assessor_feedback)
                                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                                <p class="text-sm text-gray-700"><strong>Feedback:</strong> {{ $criterion->assessor_feedback }}</p>
                                            </div>
                                        @endif

                                        @if($criterion->assessed_at)
                                            <div class="mt-2 text-xs text-gray-500">
                                                Assessed at {{ $criterion->assessed_at->format('d M Y H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Toggle Critical -->
                                    <div>
                                        <form action="{{ route('admin.assessment-criteria.toggle-critical', $criterion) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-700 transition-colors" title="Toggle Critical">
                                                <span class="material-symbols-outlined text-xl">{{ $criterion->is_critical ? 'star' : 'star_border' }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Select All Helper -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button type="button" onclick="toggleAll()" class="text-sm text-blue-900 hover:text-blue-700 font-semibold">
                            Select / Deselect All
                        </button>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No criteria available for this unit</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Statistics & Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.assessment-units.show', $assessmentUnit) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        View Unit Details
                    </a>

                    <a href="{{ route('admin.assessments.show', $assessmentUnit->assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment
                    </a>

                    @if($assessmentUnit->status === 'in_progress')
                        <form action="{{ route('admin.assessment-units.complete', $assessmentUnit) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all">
                                Complete Unit Assessment
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Progress</h3>

                <div class="space-y-4">
                    <!-- Progress Bar -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-700">Completion</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $assessmentUnit->criteria->count() > 0 ? round(($assessmentUnit->criteria->where('result', '!=', 'pending')->count() / $assessmentUnit->criteria->count()) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-900 h-2 rounded-full" style="width: {{ $assessmentUnit->criteria->count() > 0 ? ($assessmentUnit->criteria->where('result', '!=', 'pending')->count() / $assessmentUnit->criteria->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Criteria</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->criteria->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Competent</span>
                            <span class="text-sm font-semibold text-green-600">{{ $assessmentUnit->criteria->where('result', 'competent')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Not Yet Competent</span>
                            <span class="text-sm font-semibold text-red-600">{{ $assessmentUnit->criteria->where('result', 'not_yet_competent')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pending</span>
                            <span class="text-sm font-semibold text-gray-600">{{ $assessmentUnit->criteria->where('result', 'pending')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Critical Criteria</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ $assessmentUnit->criteria->where('is_critical', true)->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Critical Met</span>
                            <span class="text-sm font-semibold text-green-600">
                                {{ $assessmentUnit->criteria->where('is_critical', true)->where('result', 'competent')->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Current Score -->
                    @if($assessmentUnit->score)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 mb-1">Current Score</div>
                                <div class="text-3xl font-bold text-blue-900">{{ number_format($assessmentUnit->score, 1) }}%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAll() {
            const checkboxes = document.querySelectorAll('input[name="criteria_ids[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        }
    </script>
@endsection
