@extends('layouts.admin')

@section('title', 'Edit TUK Schedule')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit TUK Schedule</h1>
        <p class="text-gray-600 mt-2">Update schedule information</p>
    </div>

    <form action="{{ route('admin.tuk-schedules.update', $tukSchedule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Schedule Information</h2>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-medium text-gray-700 mb-2">TUK <span class="text-red-500">*</span></label>
                            <select id="tuk_id" name="tuk_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tuk_id') border-red-500 @enderror">
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
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                            <input type="date" id="date" name="date" value="{{ old('date', $tukSchedule->date->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $tukSchedule->start_time) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_time') border-red-500 @enderror">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $tukSchedule->end_time) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after start time</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
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
                            <label for="available_capacity" class="block text-sm font-medium text-gray-700 mb-2">Available Capacity</label>
                            <input type="number" id="available_capacity" name="available_capacity" value="{{ old('available_capacity', $tukSchedule->available_capacity) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('available_capacity') border-red-500 @enderror">
                            @error('available_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave empty to use TUK's full capacity</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $tukSchedule->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>

                    <div class="space-y-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Update Schedule
                            </span>
                        </button>

                        <a href="{{ route('admin.tuk-schedules.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Schedule Info</h3>
                        <dl class="text-xs text-gray-600 space-y-2">
                            @if($tukSchedule->creator)
                            <div>
                                <dt class="font-semibold">Created By</dt>
                                <dd>{{ $tukSchedule->creator->name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="font-semibold">Created</dt>
                                <dd>{{ $tukSchedule->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Last Updated</dt>
                                <dd>{{ $tukSchedule->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
    </script>
</div>
@endsection
