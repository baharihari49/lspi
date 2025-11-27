@extends('layouts.admin')

@section('title', 'Conduct Review')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'Conduct APL-02 Review')
@section('page_description', 'Conduct portfolio assessment and provide VATUK evaluation')

@section('content')
    <form action="{{ route('admin.apl02.reviews.submit-review', $review) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Review Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Review Information</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $review->review_number }}</p>
                        </div>
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
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Review Type</label>
                            <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $review->review_type)) }}</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Assessor</label>
                            <p class="text-gray-900">{{ $review->assessor->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Unit Preview -->
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
                        <a href="{{ route('admin.apl02.units.show', $review->apl02Unit) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            <span class="material-symbols-outlined text-lg">open_in_new</span>
                            <span>View Full Portfolio (Opens in New Tab)</span>
                        </a>
                    </div>
                </div>

                <!-- Evidence Summary -->
                @if($review->apl02Unit->evidence->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Summary</h3>

                        <div class="space-y-3">
                            @foreach($review->apl02Unit->evidence as $evidence)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <span class="material-symbols-outlined text-blue-600 mt-0.5">description</span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 text-sm">{{ $evidence->title }}</p>
                                                <p class="text-xs text-gray-600 mt-0.5">{{ $evidence->evidence_number }} • {{ ucfirst(str_replace('_', ' ', $evidence->evidence_type)) }}</p>
                                            </div>
                                            @php
                                                $verifyColors = [
                                                    'pending' => 'bg-gray-100 text-gray-800',
                                                    'verified' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'requires_clarification' => 'bg-yellow-100 text-yellow-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $verifyColors[$evidence->verification_status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $evidence->verification_status)) }}
                                            </span>
                                        </div>
                                        <!-- Element Mapping Section -->
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-600 font-semibold mb-1">Mapped to Elements:</p>
                                            @if($evidence->evidenceMaps->isNotEmpty())
                                                <div class="flex flex-wrap gap-1 mb-2">
                                                    @foreach($evidence->evidenceMaps as $map)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-xs group">
                                                            {{ $map->schemeElement->code }} - {{ Str::limit($map->schemeElement->title, 20) }}
                                                            <button type="button" onclick="removeMapping({{ $evidence->id }}, {{ $map->id }})" class="text-blue-600 hover:text-red-600 transition" title="Remove mapping">
                                                                <span class="material-symbols-outlined text-xs">close</span>
                                                            </button>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-yellow-600 mb-2 italic">Belum dipetakan ke elemen</p>
                                            @endif

                                            <!-- Inline Mapping Form -->
                                            @php
                                                $mappedElementIds = $evidence->evidenceMaps->pluck('scheme_element_id')->toArray();
                                                $unmappedElements = $availableElements->filter(fn($el) => !in_array($el->id, $mappedElementIds));
                                            @endphp
                                            @if($unmappedElements->isNotEmpty())
                                                <div id="mapping-form-{{ $evidence->id }}" class="hidden mt-2 p-3 bg-gray-100 rounded-lg border border-gray-300">
                                                    <div class="grid grid-cols-1 gap-2">
                                                        <select id="element-select-{{ $evidence->id }}" class="w-full h-8 px-2 text-xs rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                            <option value="">-- Pilih Elemen --</option>
                                                            @foreach($unmappedElements as $element)
                                                                <option value="{{ $element->id }}">{{ $element->code }} - {{ $element->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select id="coverage-select-{{ $evidence->id }}" class="w-full h-8 px-2 text-xs rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                            <option value="full">Full - Memenuhi seluruh kriteria</option>
                                                            <option value="partial">Partial - Memenuhi sebagian</option>
                                                            <option value="supplementary">Supplementary - Bukti pendukung</option>
                                                        </select>
                                                        <div class="flex gap-2">
                                                            <button type="button" onclick="submitMapping({{ $evidence->id }})" class="flex-1 inline-flex items-center justify-center gap-1 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium transition">
                                                                <span class="material-symbols-outlined text-sm">add_link</span>
                                                                Map
                                                            </button>
                                                            <button type="button" onclick="toggleMappingForm({{ $evidence->id }})" class="px-2 py-1 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded text-xs font-medium transition">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Evidence Actions -->
                                        <div class="mt-3 pt-3 border-t border-gray-200 flex flex-wrap items-center gap-2">
                                            @if($evidence->file_path)
                                                <a href="{{ asset('storage/' . $evidence->file_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                                    View File
                                                </a>
                                            @endif

                                            @if($evidence->verification_status === 'pending')
                                                <button type="button" onclick="verifyEvidence({{ $evidence->id }}, 'verified')" class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                                    Verify
                                                </button>
                                                <button type="button" onclick="verifyEvidence({{ $evidence->id }}, 'rejected')" class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">cancel</span>
                                                    Reject
                                                </button>
                                                <button type="button" onclick="verifyEvidence({{ $evidence->id }}, 'requires_clarification')" class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">help</span>
                                                    Need Clarification
                                                </button>
                                            @elseif($evidence->verification_status === 'verified')
                                                <span class="inline-flex items-center gap-1 text-green-600 text-xs">
                                                    <span class="material-symbols-outlined text-sm">verified</span>
                                                    Verified by {{ $evidence->verifiedBy?->name ?? 'Assessor' }}
                                                </span>
                                            @elseif($evidence->verification_status === 'rejected')
                                                <span class="inline-flex items-center gap-1 text-red-600 text-xs">
                                                    <span class="material-symbols-outlined text-sm">block</span>
                                                    Rejected
                                                </span>
                                                <button type="button" onclick="verifyEvidence({{ $evidence->id }}, 'verified')" class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">undo</span>
                                                    Revert to Verified
                                                </button>
                                            @endif

                                            <!-- Map to Element Button -->
                                            @if($unmappedElements->isNotEmpty())
                                                <button type="button" onclick="toggleMappingForm({{ $evidence->id }})" class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded text-xs font-medium transition">
                                                    <span class="material-symbols-outlined text-sm">add_link</span>
                                                    Map to Element
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- VATUK Assessment Scores -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">VATUK Assessment Scores</h3>
                    <p class="text-sm text-gray-600 mb-4">Provide scores for each assessment criterion (0-100). The overall score will be calculated automatically.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Validity -->
                        <div>
                            <label for="validity_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Validity (V) - Does the evidence demonstrate the required competency?
                            </label>
                            <input type="number" id="validity_score" name="validity_score" min="0" max="100" step="0.1"
                                value="{{ old('validity_score', $review->validity_score) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('validity_score') border-red-500 @enderror">
                            @error('validity_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Authenticity -->
                        <div>
                            <label for="authenticity_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Authenticity (A) - Is the evidence the assessee's own work?
                            </label>
                            <input type="number" id="authenticity_score" name="authenticity_score" min="0" max="100" step="0.1"
                                value="{{ old('authenticity_score', $review->authenticity_score) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('authenticity_score') border-red-500 @enderror">
                            @error('authenticity_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency (Terkini) -->
                        <div>
                            <label for="currency_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Currency/Terkini (T) - Is the evidence current and up-to-date?
                            </label>
                            <input type="number" id="currency_score" name="currency_score" min="0" max="100" step="0.1"
                                value="{{ old('currency_score', $review->currency_score) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('currency_score') border-red-500 @enderror">
                            @error('currency_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sufficiency (Utuh) -->
                        <div>
                            <label for="sufficiency_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Sufficiency/Utuh (U) - Is there enough evidence to demonstrate competency?
                            </label>
                            <input type="number" id="sufficiency_score" name="sufficiency_score" min="0" max="100" step="0.1"
                                value="{{ old('sufficiency_score', $review->sufficiency_score) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('sufficiency_score') border-red-500 @enderror">
                            @error('sufficiency_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Consistency (Konsisten) -->
                        <div>
                            <label for="consistency_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Consistency/Konsisten (K) - Is the evidence consistent across different items?
                            </label>
                            <input type="number" id="consistency_score" name="consistency_score" min="0" max="100" step="0.1"
                                value="{{ old('consistency_score', $review->consistency_score) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('consistency_score') border-red-500 @enderror">
                            @error('consistency_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Decision -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Decision</h3>

                    <div>
                        <label for="decision" class="block text-sm font-semibold text-gray-700 mb-2">Decision *</label>
                        <select id="decision" name="decision" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('decision') border-red-500 @enderror">
                            <option value="">Select Decision</option>
                            <option value="competent" {{ old('decision', $review->decision) === 'competent' ? 'selected' : '' }}>Competent</option>
                            <option value="not_yet_competent" {{ old('decision', $review->decision) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                            <option value="requires_more_evidence" {{ old('decision', $review->decision) === 'requires_more_evidence' ? 'selected' : '' }}>Requires More Evidence</option>
                            <option value="requires_demonstration" {{ old('decision', $review->decision) === 'requires_demonstration' ? 'selected' : '' }}>Requires Demonstration</option>
                            <option value="deferred" {{ old('decision', $review->decision) === 'deferred' ? 'selected' : '' }}>Deferred</option>
                            <option value="pending" {{ old('decision', $review->decision) === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('decision')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Comments and Feedback -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Comments and Feedback</h3>

                    <div class="space-y-4">
                        <!-- Overall Comments -->
                        <div>
                            <label for="overall_comments" class="block text-sm font-semibold text-gray-700 mb-2">Overall Comments</label>
                            <textarea id="overall_comments" name="overall_comments" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('overall_comments') border-red-500 @enderror">{{ old('overall_comments', $review->overall_comments) }}</textarea>
                            @error('overall_comments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Strengths -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Strengths</label>
                            <div id="strengths-container" class="space-y-2">
                                @if(old('strengths') || $review->strengths)
                                    @foreach(old('strengths', $review->strengths ?? []) as $index => $strength)
                                        <div class="flex gap-2">
                                            <input type="text" name="strengths[]" value="{{ $strength }}"
                                                class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                                placeholder="Enter strength">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input type="text" name="strengths[]"
                                            class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            placeholder="Enter strength">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addStrength()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                <span>Add Strength</span>
                            </button>
                        </div>

                        <!-- Weaknesses -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Weaknesses</label>
                            <div id="weaknesses-container" class="space-y-2">
                                @if(old('weaknesses') || $review->weaknesses)
                                    @foreach(old('weaknesses', $review->weaknesses ?? []) as $index => $weakness)
                                        <div class="flex gap-2">
                                            <input type="text" name="weaknesses[]" value="{{ $weakness }}"
                                                class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                                placeholder="Enter weakness">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input type="text" name="weaknesses[]"
                                            class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            placeholder="Enter weakness">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addWeakness()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                <span>Add Weakness</span>
                            </button>
                        </div>

                        <!-- Improvement Areas -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Areas for Improvement</label>
                            <div id="improvements-container" class="space-y-2">
                                @if(old('improvement_areas') || $review->improvement_areas)
                                    @foreach(old('improvement_areas', $review->improvement_areas ?? []) as $index => $area)
                                        <div class="flex gap-2">
                                            <input type="text" name="improvement_areas[]" value="{{ $area }}"
                                                class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                                placeholder="Enter improvement area">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input type="text" name="improvement_areas[]"
                                            class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            placeholder="Enter improvement area">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addImprovement()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                <span>Add Improvement Area</span>
                            </button>
                        </div>

                        <!-- Next Steps -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Next Steps</label>
                            <div id="nextsteps-container" class="space-y-2">
                                @if(old('next_steps') || $review->next_steps)
                                    @foreach(old('next_steps', $review->next_steps ?? []) as $index => $step)
                                        <div class="flex gap-2">
                                            <input type="text" name="next_steps[]" value="{{ $step }}"
                                                class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                                placeholder="Enter next step">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input type="text" name="next_steps[]"
                                            class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            placeholder="Enter next step">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addNextStep()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                <span>Add Next Step</span>
                            </button>
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                            <textarea id="recommendations" name="recommendations" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror">{{ old('recommendations', $review->recommendations) }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Requirements -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Requirements</h3>

                    <div class="space-y-4">
                        <!-- Requires Interview -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="requires_interview" name="requires_interview" value="1"
                                {{ old('requires_interview', $review->requires_interview) ? 'checked' : '' }}
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <div class="flex-1">
                                <label for="requires_interview" class="block text-sm font-semibold text-gray-700 cursor-pointer">Requires Interview</label>
                                <p class="text-xs text-gray-600 mt-0.5">Check if an interview is required to verify competency</p>
                            </div>
                        </div>

                        <div>
                            <label for="interview_notes" class="block text-sm font-semibold text-gray-700 mb-2">Interview Notes</label>
                            <textarea id="interview_notes" name="interview_notes" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('interview_notes', $review->interview_notes) }}</textarea>
                        </div>

                        <!-- Requires Demonstration -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="requires_demonstration" name="requires_demonstration" value="1"
                                {{ old('requires_demonstration', $review->requires_demonstration) ? 'checked' : '' }}
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <div class="flex-1">
                                <label for="requires_demonstration" class="block text-sm font-semibold text-gray-700 cursor-pointer">Requires Demonstration</label>
                                <p class="text-xs text-gray-600 mt-0.5">Check if a practical demonstration is required</p>
                            </div>
                        </div>

                        <div>
                            <label for="demonstration_notes" class="block text-sm font-semibold text-gray-700 mb-2">Demonstration Notes</label>
                            <textarea id="demonstration_notes" name="demonstration_notes" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('demonstration_notes', $review->demonstration_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>Submit Review</span>
                        </button>

                        <a href="{{ route('admin.apl02.reviews.show', $review) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Assessment Guidelines:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Review all evidence items carefully</li>
                            <li>• Ensure evidence maps to competency elements</li>
                            <li>• Provide VATUK scores for each criterion</li>
                            <li>• Justify your decision with clear comments</li>
                            <li>• Specify additional requirements if needed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function addStrength() {
            const container = document.getElementById('strengths-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="strengths[]"
                    class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Enter strength">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            container.appendChild(div);
        }

        function addWeakness() {
            const container = document.getElementById('weaknesses-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="weaknesses[]"
                    class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Enter weakness">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            container.appendChild(div);
        }

        function addImprovement() {
            const container = document.getElementById('improvements-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="improvement_areas[]"
                    class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Enter improvement area">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            container.appendChild(div);
        }

        function addNextStep() {
            const container = document.getElementById('nextsteps-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="next_steps[]"
                    class="flex-1 h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Enter next step">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            container.appendChild(div);
        }

        async function verifyEvidence(evidenceId, status) {
            const statusLabels = {
                'verified': 'Verify',
                'rejected': 'Reject',
                'requires_clarification': 'Request Clarification'
            };

            const confirmMessage = status === 'rejected'
                ? 'Are you sure you want to reject this evidence? Please provide a reason.'
                : `Are you sure you want to ${statusLabels[status]?.toLowerCase() || status} this evidence?`;

            let notes = null;
            if (status === 'rejected' || status === 'requires_clarification') {
                notes = prompt(confirmMessage + '\n\nPlease enter notes (required for rejection):');
                if (notes === null) return; // User cancelled
                if (status === 'rejected' && !notes.trim()) {
                    alert('Notes are required for rejection.');
                    return;
                }
            } else {
                if (!confirm(confirmMessage)) return;
            }

            try {
                const response = await fetch(`/admin/apl02/evidence/${evidenceId}/update-verification-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status,
                        notes: notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update verification status.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating verification status.');
            }
        }

        // Toggle mapping form visibility
        function toggleMappingForm(evidenceId) {
            const form = document.getElementById(`mapping-form-${evidenceId}`);
            if (form) {
                form.classList.toggle('hidden');
            }
        }

        // Submit mapping to element
        async function submitMapping(evidenceId) {
            const elementSelect = document.getElementById(`element-select-${evidenceId}`);
            const coverageSelect = document.getElementById(`coverage-select-${evidenceId}`);

            const elementId = elementSelect.value;
            const coverageLevel = coverageSelect.value;

            if (!elementId) {
                alert('Pilih elemen terlebih dahulu.');
                return;
            }

            try {
                const response = await fetch(`/admin/apl02/evidence/${evidenceId}/map-to-element`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        scheme_element_id: elementId,
                        coverage_level: coverageLevel
                    })
                });

                if (response.ok) {
                    location.reload();
                } else {
                    const data = await response.json();
                    alert(data.message || 'Gagal mapping evidence ke elemen.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mapping evidence.');
            }
        }

        // Remove mapping from element
        async function removeMapping(evidenceId, mapId) {
            if (!confirm('Hapus mapping ini?')) return;

            try {
                const response = await fetch(`/admin/apl02/evidence/${evidenceId}/unmap/${mapId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal menghapus mapping.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus mapping.');
            }
        }
    </script>
@endsection
