@extends('layouts.admin')

@section('title', 'Edit Session')

@php
    $active = 'events';
@endphp

@section('page_title', 'Edit Session')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <form action="{{ route('admin.events.sessions.update', [$event, $session]) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Session Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Session Information</h3>

                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Session Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $session->name) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="e.g., Day 1 - Theory Session">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Type -->
                        <div>
                            <label for="session_type" class="block text-sm font-semibold text-gray-700 mb-2">Session Type *</label>
                            <select id="session_type" name="session_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('session_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="theory" {{ old('session_type', $session->session_type) == 'theory' ? 'selected' : '' }}>Theory</option>
                                <option value="practice" {{ old('session_type', $session->session_type) == 'practice' ? 'selected' : '' }}>Practice</option>
                                <option value="exam" {{ old('session_type', $session->session_type) == 'exam' ? 'selected' : '' }}>Exam</option>
                                <option value="other" {{ old('session_type', $session->session_type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('session_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Session description...">{{ old('description', $session->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Schedule</h3>

                    <div class="space-y-4">
                        <!-- Session Date -->
                        <div>
                            <label for="session_date" class="block text-sm font-semibold text-gray-700 mb-2">Session Date *</label>
                            <input type="date" id="session_date" name="session_date" value="{{ old('session_date', $session->session_date?->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('session_date') border-red-500 @enderror">
                            @error('session_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $session->start_time) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_time') border-red-500 @enderror">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $session->end_time) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after start time</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Capacity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Location & Capacity</h3>

                    <div class="space-y-4">
                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $session->location) }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror"
                                placeholder="e.g., Room A, Building 1">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Participants -->
                        <div>
                            <label for="max_participants" class="block text-sm font-semibold text-gray-700 mb-2">Max Participants</label>
                            <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $session->max_participants) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('max_participants') border-red-500 @enderror"
                                placeholder="Leave empty for unlimited">
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Mandatory -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', $session->is_mandatory) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Mandatory Session</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">All participants must attend this session</p>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Session Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Fill all required fields (*)</li>
                                    <li>• Set session date within event dates</li>
                                    <li>• Specify start and end time</li>
                                    <li>• Mark mandatory sessions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Session</span>
                            </button>
                            <a href="{{ route('admin.events.sessions.index', $event) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Metadata</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Created At</p>
                                <p class="text-gray-900">{{ $session->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                                <p class="text-gray-900">{{ $session->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Type Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Session Type Guide</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Theory:</span>
                                    <span class="block text-xs">Classroom learning session</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Practice:</span>
                                    <span class="block text-xs">Hands-on practical session</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Exam:</span>
                                    <span class="block text-xs">Assessment or test session</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-gray-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Other:</span>
                                    <span class="block text-xs">Other session types</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
