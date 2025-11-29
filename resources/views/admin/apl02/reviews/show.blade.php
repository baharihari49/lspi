@extends('layouts.admin')

@section('title', 'Review Details')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'APL-02 Assessor Review Details')
@section('page_description', 'View detailed assessor review information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Review Information</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $review->review_number }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'submitted' => 'bg-indigo-100 text-indigo-800',
                                'approved' => 'bg-purple-100 text-purple-800',
                                'revision_required' => 'bg-yellow-100 text-yellow-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-lg text-sm font-semibold {{ $statusColors[$review->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                        </span>
                        @if($review->is_final)
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-lg text-sm font-semibold">
                                FINAL
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Review Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $review->review_type)) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Decision</label>
                        @php
                            $decisionColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'competent' => 'bg-green-100 text-green-800',
                                'not_yet_competent' => 'bg-red-100 text-red-800',
                                'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                'requires_demonstration' => 'bg-yellow-100 text-yellow-800',
                                'deferred' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessor</label>
                        <p class="text-gray-900">{{ $review->assessor->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deadline</label>
                        <p class="text-gray-900">{{ $review->deadline ? $review->deadline->format('d M Y') : '-' }}</p>
                    </div>

                    @if($review->review_started_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Started At</label>
                            <p class="text-gray-900">{{ $review->review_started_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif

                    @if($review->review_completed_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Completed At</label>
                            <p class="text-gray-900">{{ $review->review_completed_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Portfolio Unit Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Portfolio Unit</h3>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Code</label>
                            <p class="text-gray-900">{{ $review->apl02Unit->unit_code }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                            <p class="text-gray-900">{{ $review->apl02Unit->assessee->full_name }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Title</label>
                        <p class="text-gray-900">{{ $review->apl02Unit->unit_title }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $review->apl02Unit->status)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Progress</label>
                            <p class="text-sm text-gray-900">{{ number_format($review->apl02Unit->completion_percentage, 0) }}%</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Evidence</label>
                            <p class="text-sm text-gray-900">{{ $review->apl02Unit->evidence->count() }} items</p>
                        </div>
                    </div>

                    @if($review->apl02Unit->schemeUnit && $review->apl02Unit->schemeUnit->elements->isNotEmpty())
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Competency Elements</label>
                            <div class="space-y-1">
                                @foreach($review->apl02Unit->schemeUnit->elements as $element)
                                    <div class="text-sm text-gray-700 flex items-start gap-2">
                                        <span class="material-symbols-outlined text-blue-600 text-sm mt-0.5">check_circle</span>
                                        <span>{{ $element->code }} - {{ $element->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.apl02.units.show', $review->apl02Unit) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                        <span>View Full Portfolio Unit</span>
                    </a>
                </div>
            </div>

            <!-- VATUK Assessment Scores -->
            @if($review->overall_score)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">VATUK Assessment Scores</h3>

                    <div class="space-y-4">
                        <!-- Overall Score -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Overall Score</span>
                                <span class="text-2xl font-bold text-blue-900">{{ number_format($review->overall_score, 1) }}</span>
                            </div>
                        </div>

                        <!-- Individual Scores -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($review->validity_score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-semibold text-gray-700">Validity (V)</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($review->validity_score, 1) }}</span>
                                </div>
                            @endif

                            @if($review->authenticity_score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-semibold text-gray-700">Authenticity (A)</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($review->authenticity_score, 1) }}</span>
                                </div>
                            @endif

                            @if($review->currency_score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-semibold text-gray-700">Currency (T)</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($review->currency_score, 1) }}</span>
                                </div>
                            @endif

                            @if($review->sufficiency_score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-semibold text-gray-700">Sufficiency (U)</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($review->sufficiency_score, 1) }}</span>
                                </div>
                            @endif

                            @if($review->consistency_score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-semibold text-gray-700">Consistency (K)</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($review->consistency_score, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assessment Comments -->
            @if($review->overall_comments || $review->strengths || $review->weaknesses || $review->improvement_areas)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Comments</h3>

                    <div class="space-y-4">
                        @if($review->overall_comments)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Overall Comments</label>
                                <p class="text-gray-900 text-sm">{{ $review->overall_comments }}</p>
                            </div>
                        @endif

                        @if($review->strengths)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Strengths</label>
                                <ul class="space-y-1">
                                    @foreach($review->strengths as $strength)
                                        <li class="flex items-start gap-2 text-sm text-gray-900">
                                            <span class="material-symbols-outlined text-green-600 text-sm mt-0.5">check_circle</span>
                                            <span>{{ $strength }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($review->weaknesses)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Weaknesses</label>
                                <ul class="space-y-1">
                                    @foreach($review->weaknesses as $weakness)
                                        <li class="flex items-start gap-2 text-sm text-gray-900">
                                            <span class="material-symbols-outlined text-orange-600 text-sm mt-0.5">warning</span>
                                            <span>{{ $weakness }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($review->improvement_areas)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Areas for Improvement</label>
                                <ul class="space-y-1">
                                    @foreach($review->improvement_areas as $area)
                                        <li class="flex items-start gap-2 text-sm text-gray-900">
                                            <span class="material-symbols-outlined text-blue-600 text-sm mt-0.5">lightbulb</span>
                                            <span>{{ $area }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($review->recommendations)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                                <p class="text-gray-900 text-sm">{{ $review->recommendations }}</p>
                            </div>
                        @endif

                        @if($review->next_steps)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Next Steps</label>
                                <ul class="space-y-1">
                                    @foreach($review->next_steps as $step)
                                        <li class="flex items-start gap-2 text-sm text-gray-900">
                                            <span class="material-symbols-outlined text-purple-600 text-sm mt-0.5">arrow_forward</span>
                                            <span>{{ $step }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Additional Requirements -->
            @if($review->requires_interview || $review->requires_demonstration)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Requirements</h3>

                    <div class="space-y-3">
                        @if($review->requires_interview)
                            <div class="flex items-start gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <span class="material-symbols-outlined text-yellow-600 mt-0.5">forum</span>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 text-sm">Interview Required</p>
                                    @if($review->interview_notes)
                                        <p class="text-sm text-gray-700 mt-1">{{ $review->interview_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($review->requires_demonstration)
                            <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600 mt-0.5">science</span>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 text-sm">Demonstration Required</p>
                                    @if($review->demonstration_notes)
                                        <p class="text-sm text-gray-700 mt-1">{{ $review->demonstration_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Verification -->
            @if($review->verified_at)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Verification</h3>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Verified By</label>
                                <p class="text-gray-900">{{ $review->verifiedBy->name ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Verified At</label>
                                <p class="text-gray-900">{{ $review->verified_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if($review->verification_notes)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Verification Notes</label>
                                <p class="text-gray-900 text-sm">{{ $review->verification_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Metadata -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-900">Actions</h3>

                <div class="space-y-2">
                    @if(in_array($review->status, ['draft', 'in_progress']))
                        <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">rate_review</span>
                            <span>Conduct Review</span>
                        </a>
                    @endif

                    @if(in_array($review->status, ['completed', 'submitted', 'approved']))
                        <form action="{{ route('admin.apl02.reviews.reopen', $review) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin membuka kembali review ini? Status akan direset ke In Progress.')">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">refresh</span>
                                <span>Reopen Review</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.apl02.reviews.edit', $review) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit Review</span>
                    </a>

                    <a href="{{ route('admin.apl02.reviews.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>

                    <form action="{{ route('admin.apl02.reviews.destroy', $review) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">delete</span>
                            <span>Delete Review</span>
                        </button>
                    </form>
                </div>

                <!-- Metadata -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Metadata</h4>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>Created:</span>
                            <span>{{ $review->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Updated:</span>
                            <span>{{ $review->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
