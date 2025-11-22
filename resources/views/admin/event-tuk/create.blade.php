@extends('layouts.admin')

@section('title', 'Assign TUK')

@php
    $active = 'events';
@endphp

@section('page_title', 'Assign TUK')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <form action="{{ route('admin.events.tuk.store', $event) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- TUK Assignment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">TUK Assignment Information</h3>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">Select TUK *</label>
                            <select id="tuk_id" name="tuk_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($availableTuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
                                        {{ $tuk->code }} - {{ $tuk->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
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

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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

                        <!-- Is Primary -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Primary TUK</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">Mark as the main TUK for this event</p>
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
                                    <li>• Select TUK to assign to event</li>
                                    <li>• Optionally assign to specific session</li>
                                    <li>• Mark primary TUK if needed</li>
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
                                <span>Assign TUK</span>
                            </button>
                            <a href="{{ route('admin.events.tuk.index', $event) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                                <span class="w-3 h-3 rounded-full bg-yellow-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Pending:</span>
                                    <span class="block text-xs">Awaiting confirmation</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Confirmed:</span>
                                    <span class="block text-xs">TUK assignment confirmed</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Cancelled:</span>
                                    <span class="block text-xs">Assignment cancelled</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
