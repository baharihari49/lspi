@extends('layouts.admin')

@section('title', 'Assign Assessor')

@php
    $active = 'events';
@endphp

@section('page_title', 'Assign Assessor')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <form action="{{ route('admin.events.assessors.store', $event) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessor Assignment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Assignment Information</h3>

                    <div class="space-y-4">
                        <!-- Assessor Selection -->
                        <div>
                            <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Assessor *</label>
                            <select id="assessor_id" name="assessor_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                                <option value="">Select Assessor</option>
                                @foreach($availableAssessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->registration_number }} - {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Selection (Optional) -->
                        <div>
                            <label for="event_session_id" class="block text-sm font-semibold text-gray-700 mb-2">Specific Session (Optional)</label>
                            <select id="event_session_id" name="event_session_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_session_id') border-red-500 @enderror">
                                <option value="">All sessions</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('event_session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} - {{ $session->session_date->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_session_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank to assign to all sessions</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role *</label>
                            <select id="role" name="role" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('role') border-red-500 @enderror">
                                <option value="">Select Role</option>
                                <option value="lead" {{ old('role') == 'lead' ? 'selected' : '' }}>Lead Assessor</option>
                                <option value="examiner" {{ old('role') == 'examiner' ? 'selected' : '' }}>Examiner</option>
                                <option value="observer" {{ old('role') == 'observer' ? 'selected' : '' }}>Observer</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="invited" {{ old('status', 'invited') == 'invited' ? 'selected' : '' }}>Invited</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Honorarium & Payment -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Honorarium & Payment</h3>

                    <div class="space-y-4">
                        <!-- Honorarium Amount -->
                        <div>
                            <label for="honorarium_amount" class="block text-sm font-semibold text-gray-700 mb-2">Honorarium Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="honorarium_amount" name="honorarium_amount" value="{{ old('honorarium_amount') }}" min="0" step="1000"
                                    class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('honorarium_amount') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('honorarium_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                            <select id="payment_status" name="payment_status"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('payment_status') border-red-500 @enderror">
                                <option value="">Select Payment Status</option>
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Help Information -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Assignment Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Select verified assessor</li>
                                    <li>• Assign to specific session if needed</li>
                                    <li>• Define assessor role</li>
                                    <li>• Set honorarium amount</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Assign Assessor</span>
                            </button>
                            <a href="{{ route('admin.events.assessors.index', $event) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Role Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Role Guide</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Lead Assessor:</span>
                                    <span class="block text-xs">Main assessor leading the assessment</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Examiner:</span>
                                    <span class="block text-xs">Conducts practical examinations</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-gray-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Observer:</span>
                                    <span class="block text-xs">Monitors and observes assessment</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
