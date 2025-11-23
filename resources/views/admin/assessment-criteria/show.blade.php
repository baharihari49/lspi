@extends('layouts.admin')

@section('title', 'Assessment Criterion Details')

@php
    $active = 'assessment-criteria';
@endphp

@section('page_title', $assessmentCriterion->element_code)
@section('page_description', $assessmentCriterion->element_title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Criterion Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Criterion Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Element Code</label>
                        <p class="text-gray-900">{{ $assessmentCriterion->element_code }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Critical Criterion</label>
                        @if($assessmentCriterion->is_critical)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                Critical
                            </span>
                        @else
                            <span class="text-gray-500">No</span>
                        @endif
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Element Title</label>
                        <p class="text-gray-900">{{ $assessmentCriterion->element_title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment Unit</label>
                        <a href="{{ route('admin.assessment-units.show', $assessmentCriterion->assessmentUnit) }}" class="text-blue-900 hover:underline">
                            {{ $assessmentCriterion->assessmentUnit->unit_code }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment</label>
                        <a href="{{ route('admin.assessments.show', $assessmentCriterion->assessmentUnit->assessment) }}" class="text-blue-900 hover:underline">
                            {{ $assessmentCriterion->assessmentUnit->assessment->assessment_number }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                        <p class="text-gray-900">{{ $assessmentCriterion->assessmentUnit->assessment->assessee->full_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessor</label>
                        <p class="text-gray-900">{{ $assessmentCriterion->assessmentUnit->assessor ? $assessmentCriterion->assessmentUnit->assessor->name : 'Not assigned' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment Method</label>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ ucwords(str_replace('_', ' ', $assessmentCriterion->assessment_method)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Result</label>
                        @php
                            $resultColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'competent' => 'bg-green-100 text-green-800',
                                'not_yet_competent' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $resultColors[$assessmentCriterion->result] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $assessmentCriterion->result)) }}
                        </span>
                    </div>
                </div>

                @if($assessmentCriterion->evidence_collected)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Evidence Collected</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentCriterion->evidence_collected }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentCriterion->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentCriterion->notes }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentCriterion->assessor_feedback)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assessor Feedback</label>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentCriterion->assessor_feedback }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentCriterion->assessed_at)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessed At</label>
                        <p class="text-gray-900">{{ $assessmentCriterion->assessed_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>

            <!-- Quick Assessment -->
            @if($assessmentCriterion->result === 'pending' || true)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Assessment</h3>

                    <form action="{{ route('admin.assessment-criteria.assess', $assessmentCriterion) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="result" class="block text-sm font-semibold text-gray-700 mb-2">Result *</label>
                                <select id="result" name="result" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    <option value="">Select Result</option>
                                    <option value="competent" {{ $assessmentCriterion->result === 'competent' ? 'selected' : '' }}>Competent</option>
                                    <option value="not_yet_competent" {{ $assessmentCriterion->result === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                                </select>
                            </div>

                            <div>
                                <label for="evidence_collected" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Collected</label>
                                <textarea id="evidence_collected" name="evidence_collected" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ $assessmentCriterion->evidence_collected }}</textarea>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ $assessmentCriterion->notes }}</textarea>
                            </div>

                            <div>
                                <label for="assessor_feedback" class="block text-sm font-semibold text-gray-700 mb-2">Assessor Feedback</label>
                                <textarea id="assessor_feedback" name="assessor_feedback" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ $assessmentCriterion->assessor_feedback }}</textarea>
                            </div>

                            <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all">
                                Submit Assessment
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.assessment-criteria.edit', $assessmentCriterion) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Criterion
                    </a>

                    <form action="{{ route('admin.assessment-criteria.toggle-critical', $assessmentCriterion) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full h-12 px-4 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-200 transition-all">
                            {{ $assessmentCriterion->is_critical ? 'Unmark as Critical' : 'Mark as Critical' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.assessment-units.show', $assessmentCriterion->assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Unit
                    </a>

                    <a href="{{ route('admin.assessment-criteria.assess-form', $assessmentCriterion->assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Assess All Criteria
                    </a>

                    @if($assessmentCriterion->result === 'pending')
                        <form action="{{ route('admin.assessment-criteria.destroy', $assessmentCriterion) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this criterion?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all">
                                Delete Criterion
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Context Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Context</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-600 mb-1">Unit</div>
                        <div class="font-semibold text-gray-900">{{ $assessmentCriterion->assessmentUnit->unit_code }}</div>
                    </div>

                    <div class="pt-3 border-t border-gray-200">
                        <div class="text-gray-600 mb-1">Unit Progress</div>
                        <div class="font-semibold text-gray-900">
                            {{ $assessmentCriterion->assessmentUnit->criteria->where('result', 'competent')->count() }} /
                            {{ $assessmentCriterion->assessmentUnit->criteria->count() }} Competent
                        </div>
                        @if($assessmentCriterion->assessmentUnit->score)
                            <div class="text-lg font-bold text-blue-900 mt-1">{{ number_format($assessmentCriterion->assessmentUnit->score, 1) }}%</div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-gray-200">
                        <div class="text-gray-600 mb-1">Assessment Status</div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$assessmentCriterion->assessmentUnit->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $assessmentCriterion->assessmentUnit->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
