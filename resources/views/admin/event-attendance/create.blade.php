@extends('layouts.admin')

@section('title', 'Record Attendance')

@php
    $active = 'events';
@endphp

@section('page_title', 'Record Attendance')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <form action="{{ route('admin.events.attendance.store', $event) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Attendance Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Information</h3>

                    <div class="space-y-4">
                        <!-- Participant -->
                        <div>
                            <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Participant *</label>
                            <select id="user_id" name="user_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('user_id') border-red-500 @enderror">
                                <option value="">Select Participant</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session -->
                        <div>
                            <label for="event_session_id" class="block text-sm font-semibold text-gray-700 mb-2">Session *</label>
                            <select id="event_session_id" name="event_session_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_session_id') border-red-500 @enderror">
                                <option value="">Select Session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('event_session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} - {{ $session->session_date->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_session_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check In Date/Time -->
                        <div>
                            <label for="check_in_at" class="block text-sm font-semibold text-gray-700 mb-2">Check In Date & Time *</label>
                            <input type="datetime-local" id="check_in_at" name="check_in_at" value="{{ old('check_in_at', now()->format('Y-m-d\TH:i')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('check_in_at') border-red-500 @enderror">
                            @error('check_in_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check In Method -->
                        <div>
                            <label for="check_in_method" class="block text-sm font-semibold text-gray-700 mb-2">Check In Method *</label>
                            <select id="check_in_method" name="check_in_method" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('check_in_method') border-red-500 @enderror">
                                <option value="">Select Method</option>
                                <option value="manual" {{ old('check_in_method', 'manual') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="qr_code" {{ old('check_in_method') == 'qr_code' ? 'selected' : '' }}>QR Code</option>
                                <option value="biometric" {{ old('check_in_method') == 'biometric' ? 'selected' : '' }}>Biometric</option>
                                <option value="online" {{ old('check_in_method') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('check_in_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check In Location -->
                        <div>
                            <label for="check_in_location" class="block text-sm font-semibold text-gray-700 mb-2">Check In Location</label>
                            <input type="text" id="check_in_location" name="check_in_location" value="{{ old('check_in_location') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('check_in_location') border-red-500 @enderror"
                                placeholder="e.g., Room A, Building 1">
                            @error('check_in_location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="present" {{ old('status', 'present') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="excused" {{ old('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                                <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
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
                                <h4 class="font-semibold text-blue-900 mb-2">Attendance Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Select participant and session</li>
                                    <li>• Record check-in time</li>
                                    <li>• Specify check-in method</li>
                                    <li>• Set appropriate status</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Record Attendance</span>
                            </button>
                            <a href="{{ route('admin.events.attendance.index', $event) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Status Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Status Guide</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Present:</span>
                                    <span class="block text-xs">Participant attended on time</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Absent:</span>
                                    <span class="block text-xs">Participant did not attend</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Excused:</span>
                                    <span class="block text-xs">Absence with valid reason</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Late:</span>
                                    <span class="block text-xs">Attended but late</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
