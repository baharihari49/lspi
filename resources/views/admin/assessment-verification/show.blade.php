@extends('layouts.admin')

@section('title', 'Verification Details')

@php
    $active = 'assessment-verification';
@endphp

@section('page_title', $assessmentVerification->verification_number)
@section('page_description', 'Verification details and findings')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Verification Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Verification Information</h3>
                    <a href="{{ route('admin.assessment-verification.edit', $assessmentVerification) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">verified</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessmentVerification->verification_number }}</h4>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'requires_modification' => 'bg-orange-100 text-orange-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $resultColors = [
                                        'satisfactory' => 'bg-green-100 text-green-700',
                                        'needs_minor_changes' => 'bg-yellow-100 text-yellow-700',
                                        'needs_major_changes' => 'bg-orange-100 text-orange-700',
                                        'unsatisfactory' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusColors[$assessmentVerification->verification_status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $assessmentVerification->verification_status)) }}
                                </span>
                                @if($assessmentVerification->verification_result)
                                    <span class="px-3 py-1 {{ $resultColors[$assessmentVerification->verification_result] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                        {{ ucwords(str_replace('_', ' ', $assessmentVerification->verification_result)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">assignment</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessment</p>
                                <a href="{{ route('admin.assessments.show', $assessmentVerification->assessment) }}" class="font-semibold text-blue-900 hover:underline">
                                    {{ $assessmentVerification->assessment->assessment_number }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $assessmentVerification->assessment->assessee->full_name }}</p>
                            </div>
                        </div>

                        @if($assessmentVerification->assessmentUnit)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">view_module</span>
                                <div>
                                    <p class="text-xs text-gray-600">Assessment Unit</p>
                                    <a href="{{ route('admin.assessment-units.show', $assessmentVerification->assessmentUnit) }}" class="font-semibold text-blue-900 hover:underline">
                                        {{ $assessmentVerification->assessmentUnit->unit_code }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ Str::limit($assessmentVerification->assessmentUnit->unit_title, 40) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">badge</span>
                            <div>
                                <p class="text-xs text-gray-600">Verifier</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentVerification->verifier->name }}</p>
                                <p class="text-xs text-gray-500">Assessor/Verifier</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">layers</span>
                            <div>
                                <p class="text-xs text-gray-600">Verification Level</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    {{ ucwords(str_replace('_', ' ', $assessmentVerification->verification_level)) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">category</span>
                            <div>
                                <p class="text-xs text-gray-600">Verification Type</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ ucwords(str_replace('_', ' ', $assessmentVerification->verification_type)) }}
                                </span>
                            </div>
                        </div>

                        @if($assessmentVerification->verified_at)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                                <div>
                                    <p class="text-xs text-gray-600">Verified At</p>
                                    <p class="font-semibold text-gray-900">{{ $assessmentVerification->verified_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $assessmentVerification->verified_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessmentVerification->verification_duration_minutes)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">schedule</span>
                                <div>
                                    <p class="text-xs text-gray-600">Duration</p>
                                    <p class="font-semibold text-gray-900">{{ $assessmentVerification->verification_duration_minutes }} minutes</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Compliance Check -->
            @if($assessmentVerification->meets_standards !== null || $assessmentVerification->evidence_sufficient !== null || $assessmentVerification->assessment_fair !== null || $assessmentVerification->documentation_complete !== null)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Compliance Check</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($assessmentVerification->meets_standards !== null)
                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $assessmentVerification->meets_standards ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="material-symbols-outlined {{ $assessmentVerification->meets_standards ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $assessmentVerification->meets_standards ? 'check_circle' : 'cancel' }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Meets Standards</p>
                                    <p class="text-sm text-gray-600">{{ $assessmentVerification->meets_standards ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessmentVerification->evidence_sufficient !== null)
                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $assessmentVerification->evidence_sufficient ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="material-symbols-outlined {{ $assessmentVerification->evidence_sufficient ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $assessmentVerification->evidence_sufficient ? 'check_circle' : 'cancel' }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Evidence Sufficient</p>
                                    <p class="text-sm text-gray-600">{{ $assessmentVerification->evidence_sufficient ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessmentVerification->assessment_fair !== null)
                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $assessmentVerification->assessment_fair ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="material-symbols-outlined {{ $assessmentVerification->assessment_fair ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $assessmentVerification->assessment_fair ? 'check_circle' : 'cancel' }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Assessment Fair</p>
                                    <p class="text-sm text-gray-600">{{ $assessmentVerification->assessment_fair ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessmentVerification->documentation_complete !== null)
                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $assessmentVerification->documentation_complete ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="material-symbols-outlined {{ $assessmentVerification->documentation_complete ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $assessmentVerification->documentation_complete ? 'check_circle' : 'cancel' }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Documentation Complete</p>
                                    <p class="text-sm text-gray-600">{{ $assessmentVerification->documentation_complete ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Findings -->
            @if($assessmentVerification->findings)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Findings</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->findings }}</p>
                    </div>
                </div>
            @endif

            <!-- Strengths & Concerns -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($assessmentVerification->strengths)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Strengths</h3>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->strengths }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentVerification->concerns)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Concerns</h3>
                        <div class="bg-red-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->concerns }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Areas for Improvement -->
            @if($assessmentVerification->areas_for_improvement)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Areas for Improvement</h3>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->areas_for_improvement }}</p>
                    </div>
                </div>
            @endif

            <!-- Verifier Notes -->
            @if($assessmentVerification->verifier_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Verifier Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->verifier_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Assessor Response -->
            @if($assessmentVerification->assessor_response)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Response</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentVerification->assessor_response }}</p>
                    </div>
                </div>
            @endif

            <!-- Checklist -->
            @if($assessmentVerification->checklist && count($assessmentVerification->checklist) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Checklist</h3>
                    <div class="space-y-3">
                        @foreach($assessmentVerification->checklist as $item)
                            <div class="border border-gray-200 rounded-lg p-4 {{ isset($item['checked']) && $item['checked'] ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined {{ isset($item['checked']) && $item['checked'] ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ isset($item['checked']) && $item['checked'] ? 'check_box' : 'check_box_outline_blank' }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $item['item'] ?? '-' }}</p>
                                        @if(isset($item['notes']) && $item['notes'])
                                            <p class="text-sm text-gray-600 mt-1">{{ $item['notes'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Metadata -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.assessment-verification.edit', $assessmentVerification) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Verification
                    </a>

                    <a href="{{ route('admin.assessments.show', $assessmentVerification->assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment
                    </a>

                    @if($assessmentVerification->assessmentUnit)
                        <a href="{{ route('admin.assessment-units.show', $assessmentVerification->assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            View Unit
                        </a>
                    @endif

                    <a href="{{ route('admin.assessment-verification.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    <form action="{{ route('admin.assessment-verification.destroy', $assessmentVerification) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this verification?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all">
                            Delete Verification
                        </button>
                    </form>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Metadata</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentVerification->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentVerification->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
