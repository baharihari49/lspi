@extends('layouts.admin')

@section('title', 'Add LSP Profile')

@php
    $active = 'lsp-profiles';
@endphp

@section('page_title', 'Add LSP Profile')
@section('page_description', 'Create new LSP organization profile')

@section('content')
    <form action="{{ route('admin.lsp-profiles.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">LSP Code *</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('code') border-red-500 @enderror"
                                placeholder="e.g., LSP-001">
                            @error('code')
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

                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">LSP Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="e.g., Lembaga Sertifikasi Profesi - Pustaka Ilmiah Elektronik">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Legal Name -->
                        <div class="md:col-span-2">
                            <label for="legal_name" class="block text-sm font-semibold text-gray-700 mb-2">Legal Name</label>
                            <input type="text" id="legal_name" name="legal_name" value="{{ old('legal_name') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('legal_name') border-red-500 @enderror"
                                placeholder="Legal entity name (if different)">
                            @error('legal_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- License Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">License Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- License Number -->
                        <div class="md:col-span-2">
                            <label for="license_number" class="block text-sm font-semibold text-gray-700 mb-2">License Number *</label>
                            <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('license_number') border-red-500 @enderror"
                                placeholder="e.g., KEP.123/BNSP/XII/2024">
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Issued Date -->
                        <div>
                            <label for="license_issued_date" class="block text-sm font-semibold text-gray-700 mb-2">License Issued Date</label>
                            <input type="date" id="license_issued_date" name="license_issued_date" value="{{ old('license_issued_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('license_issued_date') border-red-500 @enderror">
                            @error('license_issued_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Expiry Date -->
                        <div>
                            <label for="license_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">License Expiry Date</label>
                            <input type="date" id="license_expiry_date" name="license_expiry_date" value="{{ old('license_expiry_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('license_expiry_date') border-red-500 @enderror">
                            @error('license_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Accreditation Number -->
                        <div>
                            <label for="accreditation_number" class="block text-sm font-semibold text-gray-700 mb-2">Accreditation Number</label>
                            <input type="text" id="accreditation_number" name="accreditation_number" value="{{ old('accreditation_number') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('accreditation_number') border-red-500 @enderror"
                                placeholder="e.g., BNSP-LSP-123-IDN">
                            @error('accreditation_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Accreditation Expiry Date -->
                        <div>
                            <label for="accreditation_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Accreditation Expiry Date</label>
                            <input type="date" id="accreditation_expiry_date" name="accreditation_expiry_date" value="{{ old('accreditation_expiry_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('accreditation_expiry_date') border-red-500 @enderror">
                            @error('accreditation_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror"
                                placeholder="e.g., info@lsp-pie.or.id">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror"
                                placeholder="e.g., +62 21 1234567">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fax -->
                        <div>
                            <label for="fax" class="block text-sm font-semibold text-gray-700 mb-2">Fax</label>
                            <input type="text" id="fax" name="fax" value="{{ old('fax') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('fax') border-red-500 @enderror"
                                placeholder="e.g., +62 21 7654321">
                            @error('fax')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('website') border-red-500 @enderror"
                                placeholder="e.g., https://lsp-pie.or.id">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Address Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('address') border-red-500 @enderror"
                                placeholder="Street address">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror"
                                placeholder="e.g., Jakarta">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" maxlength="255"
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
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('postal_code') border-red-500 @enderror"
                                placeholder="e.g., 12345">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Organization Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Organization Information</h3>

                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Brief description of the LSP organization">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vision -->
                        <div>
                            <label for="vision" class="block text-sm font-semibold text-gray-700 mb-2">Vision</label>
                            <textarea id="vision" name="vision" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('vision') border-red-500 @enderror"
                                placeholder="Organization vision statement">{{ old('vision') }}</textarea>
                            @error('vision')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mission -->
                        <div>
                            <label for="mission" class="block text-sm font-semibold text-gray-700 mb-2">Mission</label>
                            <textarea id="mission" name="mission" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('mission') border-red-500 @enderror"
                                placeholder="Organization mission statement">{{ old('mission') }}</textarea>
                            @error('mission')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Director Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Director Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Director Name -->
                        <div>
                            <label for="director_name" class="block text-sm font-semibold text-gray-700 mb-2">Director Name</label>
                            <input type="text" id="director_name" name="director_name" value="{{ old('director_name') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('director_name') border-red-500 @enderror"
                                placeholder="Full name">
                            @error('director_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Director Position -->
                        <div>
                            <label for="director_position" class="block text-sm font-semibold text-gray-700 mb-2">Director Position</label>
                            <input type="text" id="director_position" name="director_position" value="{{ old('director_position') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('director_position') border-red-500 @enderror"
                                placeholder="e.g., Managing Director, CEO">
                            @error('director_position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Director Phone -->
                        <div>
                            <label for="director_phone" class="block text-sm font-semibold text-gray-700 mb-2">Director Phone</label>
                            <input type="text" id="director_phone" name="director_phone" value="{{ old('director_phone') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('director_phone') border-red-500 @enderror"
                                placeholder="e.g., +62 812 3456 7890">
                            @error('director_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Director Email -->
                        <div>
                            <label for="director_email" class="block text-sm font-semibold text-gray-700 mb-2">Director Email</label>
                            <input type="email" id="director_email" name="director_email" value="{{ old('director_email') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('director_email') border-red-500 @enderror"
                                placeholder="e.g., director@lsp-pie.or.id">
                            @error('director_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                <p class="font-semibold text-sm text-gray-900">Active Profile</p>
                                <p class="text-xs text-gray-600">LSP profile is active and visible</p>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Profile Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Fill all required fields (*)</li>
                                    <li>• Use unique code and license number</li>
                                    <li>• Provide accurate contact information</li>
                                    <li>• Vision and mission are recommended</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Create LSP Profile</span>
                            </button>
                            <a href="{{ route('admin.lsp-profiles.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
