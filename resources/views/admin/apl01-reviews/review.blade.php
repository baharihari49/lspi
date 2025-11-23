@extends('layouts.admin')

@section('title', 'Conduct Review')

@php
    $active = 'apl01-reviews';
@endphp

@section('page_title', 'Conduct Review')
@section('page_description', $review->form->form_number . ' - ' . $review->review_level_name)

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
        <!-- Left Column: Form Data & Review Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessee Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessee Information</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl flex-shrink-0">
                            {{ strtoupper(substr($review->form->full_name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $review->form->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $review->form->id_number }}</p>
                            <p class="text-sm text-gray-600">{{ $review->form->email }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->scheme->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Form Number</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->form_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Submitted Date</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Completion</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->completion_percentage }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Answers Review -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Form Answers</h3>

                <div class="space-y-6">
                    @php
                        $currentSection = null;
                    @endphp

                    @forelse($review->form->answers->sortBy(fn($a) => $a->formField->order) as $answer)
                        @if($answer->formField->section !== $currentSection)
                            @php $currentSection = $answer->formField->section; @endphp
                            @if(!$loop->first)
                                <div class="border-t border-gray-200 mt-6 pt-6"></div>
                            @endif
                            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-4">
                                <h4 class="font-bold text-blue-900">Section {{ $currentSection }}</h4>
                            </div>
                        @endif

                        <div class="pl-4 border-l-2 border-gray-200">
                            <div class="mb-2">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $answer->formField->field_label }}</p>
                                        @if($answer->formField->field_description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $answer->formField->field_description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium ml-2">
                                        {{ $answer->formField->field_type_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-2">
                                @if($answer->answer_text)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $answer->answer_text }}</p>
                                    </div>
                                @elseif($answer->answer_json)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($answer->answer_json, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @elseif($answer->file_path)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <a href="{{ asset('storage/' . $answer->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">attach_file</span>
                                            {{ $answer->original_filename ?? 'View File' }}
                                        </a>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 italic">No answer provided</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No answers submitted yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Review Decision Form -->
            <form action="{{ route('admin.apl01-reviews.submit-decision', $review) }}" method="POST" id="reviewForm">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Submit Review Decision</h3>

                    <div class="space-y-4">
                        <!-- Decision -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Decision *</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="decision" value="approved" required class="sr-only">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-green-600 block mb-1">check_circle</span>
                                        <span class="text-sm font-semibold">Approve</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="decision" value="rejected" required class="sr-only">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-red-600 block mb-1">cancel</span>
                                        <span class="text-sm font-semibold">Reject</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="decision" value="approved_with_notes" required class="sr-only">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-blue-600 block mb-1">note_add</span>
                                        <span class="text-sm font-semibold">Approve with Notes</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="decision" value="returned" required class="sr-only">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-orange-600 block mb-1">undo</span>
                                        <span class="text-sm font-semibold">Return</span>
                                    </div>
                                </label>
                            </div>
                            @error('decision')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div>
                            <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">Score (Optional)</label>
                            <input type="number" id="score" name="score" min="0" max="100" value="{{ old('score') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('score') border-red-500 @enderror"
                                placeholder="Enter score (0-100)">
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Review Notes -->
                        <div>
                            <label for="review_notes" class="block text-sm font-semibold text-gray-700 mb-2">Review Notes *</label>
                            <textarea id="review_notes" name="review_notes" rows="5" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('review_notes') border-red-500 @enderror"
                                placeholder="Provide detailed feedback and notes...">{{ old('review_notes') }}</textarea>
                            @error('review_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rejection Reason (conditional) -->
                        <div id="rejection_reason_field" style="display: none;">
                            <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason *</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('rejection_reason') border-red-500 @enderror"
                                placeholder="Explain why this form is being rejected...">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations (Optional)</label>
                            <textarea id="recommendations" name="recommendations" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror"
                                placeholder="Any recommendations for the assessee...">{{ old('recommendations') }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">send</span>
                            <span>Submit Review</span>
                        </button>

                        <a href="{{ route('admin.apl01-reviews.show', $review) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <p class="mt-4 text-xs text-gray-500 text-center">
                        By submitting this review, you confirm that you have thoroughly reviewed all form answers and supporting documents.
                    </p>
                </div>
            </form>
        </div>

        <!-- Right Column: Review Info & Actions -->
        <div class="space-y-6">
            <!-- Review Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Review Information</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-600">Review Level</p>
                        <p class="font-semibold text-gray-900">{{ $review->review_level_name ?? 'Level ' . $review->review_level }}</p>
                        <div class="flex gap-1 mt-1">
                            @if($review->is_current)
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">Current</span>
                            @endif
                            @if($review->is_final)
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded">Final</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-600">Assigned</p>
                        <p class="font-semibold text-gray-900">{{ $review->assigned_at->format('d M Y H:i') }}</p>
                    </div>

                    @if($review->deadline)
                        <div>
                            <p class="text-xs text-gray-600">Deadline</p>
                            <p class="font-semibold {{ $review->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $review->deadline->format('d M Y H:i') }}
                            </p>
                            @if($review->is_overdue)
                                <p class="text-xs text-red-600 font-semibold mt-1">Overdue!</p>
                            @endif
                        </div>
                    @endif

                    @if(!$review->started_at)
                        <form action="{{ route('admin.apl01-reviews.start', $review) }}" method="POST" class="pt-4 border-t border-gray-200">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition text-sm">
                                Start Review
                            </button>
                            <p class="mt-2 text-xs text-gray-500 text-center">Click to mark review as started</p>
                        </form>
                    @else
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600">Started</p>
                            <p class="font-semibold text-gray-900">{{ $review->started_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Links</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.apl01.show', $review->form) }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                        <span class="material-symbols-outlined text-sm">description</span>
                        <span>View Full Form</span>
                    </a>

                    <a href="{{ route('admin.assessees.show', $review->form->assessee) }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                        <span class="material-symbols-outlined text-sm">person</span>
                        <span>View Assessee Profile</span>
                    </a>

                    <a href="{{ route('admin.apl01-reviews.index') }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                        <span class="material-symbols-outlined text-sm">list</span>
                        <span>All Reviews</span>
                    </a>

                    <a href="{{ route('admin.apl01-reviews.my-reviews') }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                        <span class="material-symbols-outlined text-sm">assignment_ind</span>
                        <span>My Reviews</span>
                    </a>
                </div>
            </div>

            <!-- Help -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-sm font-bold text-blue-900 mb-2">Review Guidelines</h3>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li>• Review all form answers thoroughly</li>
                    <li>• Check supporting documents if uploaded</li>
                    <li>• Provide clear and constructive feedback</li>
                    <li>• Use rejection only when necessary</li>
                    <li>• Add recommendations for improvement</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- JavaScript for conditional fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decisionRadios = document.querySelectorAll('input[name="decision"]');
            const rejectionField = document.getElementById('rejection_reason_field');
            const rejectionTextarea = document.getElementById('rejection_reason');

            decisionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'rejected') {
                        rejectionField.style.display = 'block';
                        rejectionTextarea.required = true;
                    } else {
                        rejectionField.style.display = 'none';
                        rejectionTextarea.required = false;
                    }
                });
            });

            // Form submission confirmation
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                const decision = document.querySelector('input[name="decision"]:checked');
                if (!decision) {
                    e.preventDefault();
                    alert('Please select a decision before submitting.');
                    return false;
                }

                const decisionText = decision.value.replace('_', ' ').toUpperCase();
                if (!confirm(`Are you sure you want to ${decisionText} this form? This action cannot be undone.`)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
