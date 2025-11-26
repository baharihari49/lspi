@extends('layouts.admin')

@section('title', 'Assessment Result Details')

@php
    $active = 'assessment-results';
@endphp

@section('page_title', $assessmentResult->result_number)
@section('page_description', 'Assessment result details and certification information')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Result Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Result Information</h3>
                    @if($assessmentResult->approval_status !== 'approved')
                        <a href="{{ route('admin.assessment-results.edit', $assessmentResult) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Result</span>
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">description</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessmentResult->result_number }}</h4>
                            @if($assessmentResult->certificate_number)
                                <p class="text-sm text-gray-600">Certificate: {{ $assessmentResult->certificate_number }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2">
                                @php
                                    $resultColors = [
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                        'requires_reassessment' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $approvalColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'revision_required' => 'bg-orange-100 text-orange-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $resultColors[$assessmentResult->final_result] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $assessmentResult->final_result)) }}
                                </span>
                                <span class="px-3 py-1 {{ $approvalColors[$assessmentResult->approval_status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $assessmentResult->approval_status)) }}
                                </span>
                                @if($assessmentResult->is_published)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Published</span>
                                @endif
                                @if($assessmentResult->certificate_issued)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Certificate Issued</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessee</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentResult->assessee->full_name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentResult->assessee->assessee_number ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">workspace_premium</span>
                            <div>
                                <p class="text-xs text-gray-600">Scheme</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentResult->scheme->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentResult->scheme->code ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">badge</span>
                            <div>
                                <p class="text-xs text-gray-600">Lead Assessor</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentResult->leadAssessor->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">assignment</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessment</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentResult->assessment->assessment_number ?? '-' }}</p>
                            </div>
                        </div>

                        @if($assessmentResult->certification_date)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">event</span>
                                <div>
                                    <p class="text-xs text-gray-600">Certification Date</p>
                                    <p class="font-semibold text-gray-900">{{ $assessmentResult->certification_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessmentResult->certification_expiry_date)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">event_busy</span>
                                <div>
                                    <p class="text-xs text-gray-600">Expiry Date</p>
                                    <p class="font-semibold text-gray-900">{{ $assessmentResult->certification_expiry_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Performance Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Performance Statistics</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-blue-900">{{ $assessmentResult->units_assessed }}</p>
                        <p class="text-xs text-blue-700 mt-1">Units Assessed</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-green-900">{{ $assessmentResult->units_competent }}</p>
                        <p class="text-xs text-green-700 mt-1">Units Competent</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-purple-900">{{ number_format($assessmentResult->overall_score, 1) }}%</p>
                        <p class="text-xs text-purple-700 mt-1">Overall Score</p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-indigo-900">{{ $assessmentResult->criteria_met }}/{{ $assessmentResult->total_criteria }}</p>
                        <p class="text-xs text-indigo-700 mt-1">Criteria Met</p>
                    </div>
                </div>

                @if($assessmentResult->critical_criteria_total > 0)
                    <div class="p-4 {{ $assessmentResult->all_critical_criteria_met ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $assessmentResult->all_critical_criteria_met ? 'text-green-600' : 'text-red-600' }}">
                                {{ $assessmentResult->all_critical_criteria_met ? 'check_circle' : 'error' }}
                            </span>
                            <div>
                                <p class="font-semibold {{ $assessmentResult->all_critical_criteria_met ? 'text-green-900' : 'text-red-900' }}">
                                    Critical Criteria: {{ $assessmentResult->critical_criteria_met }}/{{ $assessmentResult->critical_criteria_total }}
                                </p>
                                <p class="text-xs {{ $assessmentResult->all_critical_criteria_met ? 'text-green-700' : 'text-red-700' }} mt-1">
                                    {{ $assessmentResult->all_critical_criteria_met ? 'All critical criteria have been met' : 'Not all critical criteria have been met' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Documents</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $assessmentResult->documents_submitted }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Observations</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $assessmentResult->observations_conducted }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Interviews</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $assessmentResult->interviews_conducted }}</p>
                    </div>
                </div>
            </div>

            <!-- Executive Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Executive Summary</h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $assessmentResult->executive_summary }}</p>
            </div>

            <!-- Key Strengths & Development Areas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Key Strengths -->
                @if($assessmentResult->key_strengths)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600">thumb_up</span>
                            Key Strengths
                        </h3>
                        <ul class="space-y-2">
                            @foreach($assessmentResult->key_strengths as $strength)
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-sm mt-0.5">check_circle</span>
                                    <span>{{ $strength }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Development Areas -->
                @if($assessmentResult->development_areas)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-600">trending_up</span>
                            Development Areas
                        </h3>
                        <ul class="space-y-2">
                            @foreach($assessmentResult->development_areas as $area)
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-orange-600 text-sm mt-0.5">arrow_forward</span>
                                    <span>{{ $area }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Overall Performance Notes -->
            @if($assessmentResult->overall_performance_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Overall Performance Notes</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $assessmentResult->overall_performance_notes }}</p>
                </div>
            @endif

            <!-- Recommendations -->
            @if($assessmentResult->recommendations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">lightbulb</span>
                        Recommendations
                    </h3>
                    <ul class="space-y-2">
                        @foreach($assessmentResult->recommendations as $recommendation)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <span class="material-symbols-outlined text-blue-600 text-sm mt-0.5">star</span>
                                <span>{{ $recommendation }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Next Steps -->
            @if($assessmentResult->next_steps)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Next Steps</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $assessmentResult->next_steps }}</p>
                </div>
            @endif

            <!-- Reassessment Plan -->
            @if($assessmentResult->reassessment_plan)
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">refresh</span>
                        Reassessment Plan
                    </h3>
                    <p class="text-sm text-orange-800 leading-relaxed">{{ $assessmentResult->reassessment_plan }}</p>
                </div>
            @endif

            <!-- Unit Results -->
            @if($assessmentResult->unit_results)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Results Breakdown</h3>
                    <div class="space-y-3">
                        @foreach($assessmentResult->unit_results as $unitResult)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $unitResult['unit_code'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $unitResult['unit_title'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Criteria: {{ $unitResult['criteria_met'] }}/{{ $unitResult['total_criteria'] }}
                                            @if($unitResult['score'])
                                                • Score: {{ number_format($unitResult['score'], 1) }}%
                                            @endif
                                        </p>
                                    </div>
                                    @php
                                        $unitResultColors = [
                                            'competent' => 'bg-green-100 text-green-700',
                                            'not_yet_competent' => 'bg-red-100 text-red-700',
                                            'pending' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 {{ $unitResultColors[$unitResult['result']] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold whitespace-nowrap ml-4">
                                        {{ ucwords(str_replace('_', ' ', $unitResult['result'])) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if($assessmentResult->approval_status === 'pending')
                        <form action="{{ route('admin.assessment-results.submit-for-approval', $assessmentResult) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">send</span>
                                Submit for Approval
                            </button>
                        </form>
                    @endif

                    @if($assessmentResult->approval_status !== 'approved')
                        <a href="{{ route('admin.assessment-results.edit', $assessmentResult) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Edit Result
                        </a>
                    @endif

                    @if($assessmentResult->approval_status === 'approved' && !$assessmentResult->is_published)
                        <form action="{{ route('admin.assessment-results.publish', $assessmentResult) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all">
                                Publish Result
                            </button>
                        </form>
                    @endif

                    @if($assessmentResult->final_result === 'competent' && $assessmentResult->approval_status === 'approved' && !$assessmentResult->certificate_issued)
                        <form action="{{ route('admin.assessment-results.issue-certificate', $assessmentResult) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all">
                                Issue Certificate
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.assessments.show', $assessmentResult->assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment
                    </a>

                    <a href="{{ route('admin.assessment-results.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    @if($assessmentResult->approval_status === 'pending')
                        <form action="{{ route('admin.assessment-results.destroy', $assessmentResult) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this result?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                                Delete Result
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $assessmentResult->created_at->format('d M Y H:i') }}
                    </p>
                    @if($assessmentResult->updated_at != $assessmentResult->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $assessmentResult->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                    @if($assessmentResult->published_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Published:</span><br>
                            {{ $assessmentResult->published_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Final Result</span>
                        <span class="font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $assessmentResult->final_result)) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Approval Status</span>
                        <span class="font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $assessmentResult->approval_status)) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Published</span>
                        <span class="font-bold text-gray-900">{{ $assessmentResult->is_published ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Valid</span>
                        <span class="font-bold text-gray-900">{{ $assessmentResult->is_valid ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
