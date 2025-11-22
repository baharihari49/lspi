@extends('layouts.admin')

@section('title', 'Add TUK')

@php
    $active = 'tuk';
@endphp

@section('page_title', 'Add TUK')
@section('page_description', 'Create new Tempat Uji Kompetensi (Competency Test Center)')

@section('content')
    <form action="{{ route('admin.tuk.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- TUK Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">TUK Code *</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('code') border-red-500 @enderror"
                                placeholder="e.g., TUK-001">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">TUK Type *</label>
                            <select id="type" name="type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="permanent" {{ old('type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                <option value="temporary" {{ old('type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                <option value="mobile" {{ old('type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">TUK Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="Full name of the test center">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status_id" name="status_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Manager -->
                        <div>
                            <label for="manager_id" class="block text-sm font-semibold text-gray-700 mb-2">Manager</label>
                            <select id="manager_id" name="manager_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('manager_id') border-red-500 @enderror">
                                <option value="">Select Manager</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Brief description of the test center">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Location Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                            <textarea id="address" name="address" rows="3" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('address') border-red-500 @enderror"
                                placeholder="Full address of the test center">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror"
                                placeholder="e.g., Jakarta">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Province *</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('province') border-red-500 @enderror"
                                placeholder="e.g., DKI Jakarta">
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" maxlength="10"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('postal_code') border-red-500 @enderror"
                                placeholder="12345">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="0.000001" min="-90" max="90"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('latitude') border-red-500 @enderror"
                                placeholder="-6.200000">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="0.000001" min="-180" max="180"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('longitude') border-red-500 @enderror"
                                placeholder="106.816666">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror"
                                placeholder="+62 21 1234567">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror"
                                placeholder="tuk@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PIC Name -->
                        <div>
                            <label for="pic_name" class="block text-sm font-semibold text-gray-700 mb-2">PIC Name</label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('pic_name') border-red-500 @enderror"
                                placeholder="Person In Charge name">
                            @error('pic_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PIC Phone -->
                        <div>
                            <label for="pic_phone" class="block text-sm font-semibold text-gray-700 mb-2">PIC Phone</label>
                            <input type="text" id="pic_phone" name="pic_phone" value="{{ old('pic_phone') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('pic_phone') border-red-500 @enderror"
                                placeholder="+62 812 3456 7890">
                            @error('pic_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Facility Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Facility Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Capacity *</label>
                            <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 0) }}" required min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('capacity') border-red-500 @enderror"
                                placeholder="Maximum people capacity">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Number of people</p>
                        </div>

                        <!-- Room Count -->
                        <div>
                            <label for="room_count" class="block text-sm font-semibold text-gray-700 mb-2">Room Count *</label>
                            <input type="number" id="room_count" name="room_count" value="{{ old('room_count', 0) }}" required min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('room_count') border-red-500 @enderror"
                                placeholder="Number of rooms">
                            @error('room_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Area Size -->
                        <div>
                            <label for="area_size" class="block text-sm font-semibold text-gray-700 mb-2">Area Size</label>
                            <input type="number" id="area_size" name="area_size" value="{{ old('area_size') }}" step="0.01" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('area_size') border-red-500 @enderror"
                                placeholder="Area size">
                            @error('area_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">In square meters (m²)</p>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Settings</h3>

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">Active TUK</p>
                                <p class="text-xs text-gray-600">TUK is active and can be used for assessments</p>
                            </div>
                        </label>
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
                                <h4 class="font-semibold text-blue-900 mb-2">TUK Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Fill all required fields (*)</li>
                                    <li>• Use unique TUK code</li>
                                    <li>• Provide accurate location info</li>
                                    <li>• Set appropriate capacity</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Create TUK</span>
                            </button>
                            <a href="{{ route('admin.tuk.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
