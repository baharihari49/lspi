@extends('layouts.admin')

@section('title', 'Edit Approval')

@php
    $active = 'result-approval';
@endphp

@section('page_title', 'Edit Approval')
@section('page_description', 'Update approval assignment and details')

@section('content')
    <form action="{{ route('admin.result-approval.update', $resultApproval) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessment Result Information (Read-only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Result</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Result Number</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->result_number }}</p>
                        </div>

                        @if($resultApproval->assessmentResult->certificate_number)
                            <div>
                                <p class="text-xs text-gray-600">Certificate Number</p>
                                <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->certificate_number }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs text-gray-600">Assessee</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->assessee->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->assessmentResult->assessee->assessee_number ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->scheme->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->assessmentResult->scheme->code ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Final Result</p>
                            @php
                                $resultColors = [
                                    'competent' => 'bg-green-100 text-green-700',
                                    'not_yet_competent' => 'bg-red-100 text-red-700',
                                    'requires_reassessment' => 'bg-orange-100 text-orange-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColors[$resultApproval->assessmentResult->final_result] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucwords(str_replace('_', ' ', $resultApproval->assessmentResult->final_result)) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Overall Score</p>
                            <p class="font-bold text-gray-900">{{ number_format($resultApproval->assessmentResult->overall_score, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Approval Configuration -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Configuration</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approver -->
                        <div>
                            <label for="approver_id" class="block text-sm font-semibold text-gray-700 mb-2">Approver *</label>
                            <select id="approver_id" name="approver_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Approver</option>
                                @foreach($approvers as $approver)
                                    <option value="{{ $approver->id }}" {{ old('approver_id', $resultApproval->approver_id) == $approver->id ? 'selected' : '' }}>
                                        {{ $approver->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approver_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Approver Role -->
                        <div>
                            <label for="approver_role" class="block text-sm font-semibold text-gray-700 mb-2">Approver Role *</label>
                            <select id="approver_role" name="approver_role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Role</option>
                                <option value="lead_assessor" {{ old('approver_role', $resultApproval->approver_role) === 'lead_assessor' ? 'selected' : '' }}>Lead Assessor</option>
                                <option value="technical_reviewer" {{ old('approver_role', $resultApproval->approver_role) === 'technical_reviewer' ? 'selected' : '' }}>Technical Reviewer</option>
                                <option value="quality_assurance" {{ old('approver_role', $resultApproval->approver_role) === 'quality_assurance' ? 'selected' : '' }}>Quality Assurance</option>
                                <option value="certification_manager" {{ old('approver_role', $resultApproval->approver_role) === 'certification_manager' ? 'selected' : '' }}>Certification Manager</option>
                                <option value="director" {{ old('approver_role', $resultApproval->approver_role) === 'director' ? 'selected' : '' }}>Director</option>
                                <option value="external_verifier" {{ old('approver_role', $resultApproval->approver_role) === 'external_verifier' ? 'selected' : '' }}>External Verifier</option>
                            </select>
                            @error('approver_role')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Approval Level -->
                        <div>
                            <label for="approval_level" class="block text-sm font-semibold text-gray-700 mb-2">Approval Level *</label>
                            <input type="number" id="approval_level" name="approval_level" min="1" max="10"
                                value="{{ old('approval_level', $resultApproval->approval_level) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('approval_level')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Higher levels indicate higher authority</p>
                        </div>

                        <!-- Sequence Order -->
                        <div>
                            <label for="sequence_order" class="block text-sm font-semibold text-gray-700 mb-2">Sequence Order</label>
                            <input type="number" id="sequence_order" name="sequence_order" min="0"
                                value="{{ old('sequence_order', $resultApproval->sequence_order) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('sequence_order')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Order within the same level (0 = first)</p>
                        </div>

                        <!-- Due Date -->
                        <div class="md:col-span-2">
                            <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                            <input type="date" id="due_date" name="due_date"
                                value="{{ old('due_date', $resultApproval->due_date) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('due_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Information</h3>

                    <div>
                        <label for="comments" class="block text-sm font-semibold text-gray-700 mb-2">Comments</label>
                        <textarea id="comments" name="comments" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                            placeholder="Add any additional comments or notes...">{{ old('comments', $resultApproval->comments) }}</textarea>
                        @error('comments')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                            <span class="material-symbols-outlined text-sm">save</span>
                            <span>Save Changes</span>
                        </button>

                        <a href="{{ route('admin.result-approval.show', $resultApproval) }}"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <!-- Help Tips -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Help Tips</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-blue-900 text-sm">info</span>
                                <p class="text-xs text-gray-600">Approval Level: Higher number = Higher authority</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-blue-900 text-sm">info</span>
                                <p class="text-xs text-gray-600">Sequence Order: Use this to control order within same level</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-blue-900 text-sm">info</span>
                                <p class="text-xs text-gray-600">Due Date: Approval will be marked as overdue if not completed by this date</p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Current Status</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $resultApproval->status)) }}</span>
                            </div>
                            @if($resultApproval->decision)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Decision:</span>
                                    <span class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $resultApproval->decision)) }}</span>
                                </div>
                            @endif
                            @if($resultApproval->assigned_at)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Assigned:</span>
                                    <span class="font-semibold text-gray-900">{{ $resultApproval->assigned_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
