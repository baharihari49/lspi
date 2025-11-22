@extends('layouts.admin')

@section('title', 'Edit TUK Schedule')

@php
    $active = 'tuk-schedules';
@endphp

@section('page_title', 'Edit TUK Schedule')
@section('page_description', 'Update schedule information')

@section('content')
    <form action="{{ route('admin.tuk-schedules.update', $tukSchedule) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Schedule Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Schedule Information</h3>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">TUK *</label>
                            <select id="tuk_id" name="tuk_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ (old('tuk_id', $tukSchedule->tuk_id) == $tuk->id) ? 'selected' : '' }} data-capacity="{{ $tuk->capacity }}">
                                        {{ $tuk->name }} ({{ $tuk->code }}) - Capacity: {{ $tuk->capacity }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Date *</label>
                            <input type="date" id="date" name="date" value="{{ old('date', $tukSchedule->date->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">Start Time *</label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $tukSchedule->start_time) }}" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_time') border-red-500 @enderror">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">End Time *</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $tukSchedule->end_time) }}" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after start time</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="available" {{ old('status', $tukSchedule->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ old('status', $tukSchedule->status) == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="blocked" {{ old('status', $tukSchedule->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                <option value="maintenance" {{ old('status', $tukSchedule->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Available Capacity -->
                        <div id="capacity-field">
                            <label for="available_capacity" class="block text-sm font-semibold text-gray-700 mb-2">Available Capacity</label>
                            <input type="number" id="available_capacity" name="available_capacity" value="{{ old('available_capacity', $tukSchedule->available_capacity) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('available_capacity') border-red-500 @enderror"
                                placeholder="Number of available slots">
                            @error('available_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave empty to use TUK's full capacity</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Additional notes about this schedule">{{ old('notes', $tukSchedule->notes) }}</textarea>
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
                    <!-- Current Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Current Status</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">TUK</p>
                                <p class="font-semibold text-gray-900">{{ $tukSchedule->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $tukSchedule->tuk->code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Current Date</p>
                                <p class="text-sm text-gray-900">{{ $tukSchedule->date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Current Time</p>
                                <p class="text-sm text-gray-900">{{ date('H:i', strtotime($tukSchedule->start_time)) }} - {{ date('H:i', strtotime($tukSchedule->end_time)) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-700',
                                        'booked' => 'bg-blue-100 text-blue-700',
                                        'blocked' => 'bg-red-100 text-red-700',
                                        'maintenance' => 'bg-yellow-100 text-yellow-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $statusColors[$tukSchedule->status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($tukSchedule->status) }}
                                </span>
                            </div>
                            @if($tukSchedule->creator)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Created By</p>
                                <p class="text-sm text-gray-900">{{ $tukSchedule->creator->name }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                                <p class="text-sm text-gray-900">{{ $tukSchedule->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Help Information -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Update Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Verify all information</li>
                                    <li>• Update dates carefully</li>
                                    <li>• Set appropriate status</li>
                                    <li>• Capacity auto-fills from TUK</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Schedule</span>
                            </button>
                            <a href="{{ route('admin.tuk-schedules.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                                    <span class="font-semibold text-gray-900">Available:</span>
                                    <span class="block text-xs">Open for booking</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Booked:</span>
                                    <span class="block text-xs">Reserved/in use</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Blocked:</span>
                                    <span class="block text-xs">Not available</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Maintenance:</span>
                                    <span class="block text-xs">Under maintenance</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">{{ $tukSchedule->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-semibold text-gray-900">{{ $tukSchedule->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Show/hide capacity field based on status
        const statusSelect = document.getElementById('status');
        const capacityField = document.getElementById('capacity-field');

        function toggleCapacityField() {
            const status = statusSelect.value;
            if (status === 'available' || status === 'booked') {
                capacityField.style.display = 'block';
            } else {
                capacityField.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleCapacityField);
        toggleCapacityField();

        // Auto-fill capacity based on selected TUK
        document.getElementById('tuk_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const capacity = selectedOption.getAttribute('data-capacity');
            if (capacity && statusSelect.value === 'available') {
                document.getElementById('available_capacity').value = capacity;
            }
        });
    </script>
    @endpush
@endsection
