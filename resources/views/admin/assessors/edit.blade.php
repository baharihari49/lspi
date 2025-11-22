@extends('layouts.admin')

@section('title', 'Edit Assessor')

@php
    $active = 'assessors';
@endphp

@section('page_title', 'Edit Assessor')
@section('page_description', 'Update assessor information and verification status')

@section('content')
    <form action="{{ route('admin.assessors.update', $assessor) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- User Account -->
                        <div class="md:col-span-2">
                            <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">User Account
                                *</label>
                            <select id="user_id" name="user_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('user_id') border-red-500 @enderror">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $assessor->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name
                                *</label>
                            <input type="text" id="full_name" name="full_name"
                                value="{{ old('full_name', $assessor->full_name) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('full_name') border-red-500 @enderror">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Card Number -->
                        <div>
                            <label for="id_card_number" class="block text-sm font-semibold text-gray-700 mb-2">ID Card
                                Number (NIK) *</label>
                            <input type="text" id="id_card_number" name="id_card_number"
                                value="{{ old('id_card_number', $assessor->id_card_number) }}" required maxlength="16"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('id_card_number') border-red-500 @enderror">
                            @error('id_card_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                            <select id="gender" name="gender" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $assessor->gender) === 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female"
                                    {{ old('gender', $assessor->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place -->
                        <div>
                            <label for="birth_place" class="block text-sm font-semibold text-gray-700 mb-2">Birth Place
                                *</label>
                            <input type="text" id="birth_place" name="birth_place"
                                value="{{ old('birth_place', $assessor->birth_place) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('birth_place') border-red-500 @enderror">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">Birth Date
                                *</label>
                            <input type="date" id="birth_date" name="birth_date"
                                value="{{ old('birth_date', $assessor->birth_date?->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div class="md:col-span-2">
                            <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">Photo</label>
                            @if ($assessor->photo_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($assessor->photo_path) }}" alt="{{ $assessor->full_name }}"
                                        class="w-24 h-24 rounded-lg object-cover">
                                    <p class="text-xs text-gray-500 mt-1">Current photo</p>
                                </div>
                            @endif
                            <input type="file" id="photo" name="photo" accept="image/*"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('photo') border-red-500 @enderror">
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current photo. Max size: 2MB. Formats:
                                JPG, PNG</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                            <textarea id="address" name="address" required rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('address') border-red-500 @enderror">{{ old('address', $assessor->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                            <input type="text" id="city" name="city" value="{{ old('city', $assessor->city) }}"
                                required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Province *</label>
                            <input type="text" id="province" name="province"
                                value="{{ old('province', $assessor->province) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('province') border-red-500 @enderror">
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Postal
                                Code</label>
                            <input type="text" id="postal_code" name="postal_code"
                                value="{{ old('postal_code', $assessor->postal_code) }}" maxlength="10"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('postal_code') border-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">Mobile Phone
                                *</label>
                            <input type="text" id="mobile" name="mobile"
                                value="{{ old('mobile', $assessor->mobile) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('mobile') border-red-500 @enderror">
                            @error('mobile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone"
                                value="{{ old('phone', $assessor->phone) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $assessor->email) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Education & Professional Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Education & Professional Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Education Level -->
                        <div>
                            <label for="education_level" class="block text-sm font-semibold text-gray-700 mb-2">Education
                                Level *</label>
                            <select id="education_level" name="education_level" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('education_level') border-red-500 @enderror">
                                <option value="">Select Level</option>
                                <option value="D3"
                                    {{ old('education_level', $assessor->education_level) === 'D3' ? 'selected' : '' }}>D3
                                </option>
                                <option value="S1"
                                    {{ old('education_level', $assessor->education_level) === 'S1' ? 'selected' : '' }}>S1
                                </option>
                                <option value="S2"
                                    {{ old('education_level', $assessor->education_level) === 'S2' ? 'selected' : '' }}>S2
                                </option>
                                <option value="S3"
                                    {{ old('education_level', $assessor->education_level) === 'S3' ? 'selected' : '' }}>S3
                                </option>
                            </select>
                            @error('education_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Major -->
                        <div>
                            <label for="major" class="block text-sm font-semibold text-gray-700 mb-2">Major</label>
                            <input type="text" id="major" name="major"
                                value="{{ old('major', $assessor->major) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('major') border-red-500 @enderror">
                            @error('major')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Institution -->
                        <div>
                            <label for="institution"
                                class="block text-sm font-semibold text-gray-700 mb-2">Institution</label>
                            <input type="text" id="institution" name="institution"
                                value="{{ old('institution', $assessor->institution) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('institution') border-red-500 @enderror">
                            @error('institution')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label for="occupation"
                                class="block text-sm font-semibold text-gray-700 mb-2">Occupation</label>
                            <input type="text" id="occupation" name="occupation"
                                value="{{ old('occupation', $assessor->occupation) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('occupation') border-red-500 @enderror">
                            @error('occupation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company -->
                        <div>
                            <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">Company</label>
                            <input type="text" id="company" name="company"
                                value="{{ old('company', $assessor->company) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('company') border-red-500 @enderror">
                            @error('company')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Position</label>
                            <input type="text" id="position" name="position"
                                value="{{ old('position', $assessor->position) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('position') border-red-500 @enderror">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Registration Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Registration Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Registration Date -->
                        <div>
                            <label for="registration_date"
                                class="block text-sm font-semibold text-gray-700 mb-2">Registration Date *</label>
                            <input type="date" id="registration_date" name="registration_date"
                                value="{{ old('registration_date', $assessor->registration_date?->format('Y-m-d')) }}"
                                required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('registration_date') border-red-500 @enderror">
                            @error('registration_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valid Until -->
                        <div>
                            <label for="valid_until" class="block text-sm font-semibold text-gray-700 mb-2">Valid Until
                                *</label>
                            <input type="date" id="valid_until" name="valid_until"
                                value="{{ old('valid_until', $assessor->valid_until?->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('valid_until') border-red-500 @enderror">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Status -->
                        <div>
                            <label for="registration_status"
                                class="block text-sm font-semibold text-gray-700 mb-2">Registration Status *</label>
                            <select id="registration_status" name="registration_status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('registration_status') border-red-500 @enderror">
                                <option value="active"
                                    {{ old('registration_status', $assessor->registration_status) === 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('registration_status', $assessor->registration_status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="suspended"
                                    {{ old('registration_status', $assessor->registration_status) === 'suspended' ? 'selected' : '' }}>
                                    Suspended</option>
                                <option value="expired"
                                    {{ old('registration_status', $assessor->registration_status) === 'expired' ? 'selected' : '' }}>
                                    Expired</option>
                            </select>
                            @error('registration_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status_id" name="status_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_id', $assessor->status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MET Number -->
                        <div class="md:col-span-2">
                            <label for="met_number" class="block text-sm font-semibold text-gray-700 mb-2">MET
                                Number</label>
                            <input type="text" id="met_number" name="met_number"
                                value="{{ old('met_number', $assessor->met_number) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('met_number') border-red-500 @enderror">
                            @error('met_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $assessor->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Active Assessor</p>
                                    <p class="text-xs text-gray-600">Assessor can participate in assessment activities</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="lg:sticky lg:top-0">
                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Assessor</span>
                            </button>
                            <a href="{{ route('admin.assessors.show', $assessor) }}"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Assessor Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Assessor Info</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Reg Number:</span>
                                <span class="font-mono text-xs text-gray-900">{{ $assessor->registration_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span
                                    class="font-semibold text-gray-900">{{ $assessor->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span
                                    class="font-semibold text-gray-900">{{ $assessor->updated_at->format('d M Y') }}</span>
                            </div>
                            @if ($assessor->verified_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Verified:</span>
                                    <span
                                        class="font-semibold text-gray-900">{{ $assessor->verified_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Verification Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Verification</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                @php
                                    $verificationColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-0.5 {{ $verificationColors[$assessor->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded text-xs font-semibold">
                                    {{ ucfirst($assessor->verification_status) }}
                                </span>
                            </div>
                            @if ($assessor->verifier)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Verified By:</span>
                                    <span class="font-semibold text-gray-900">{{ $assessor->verifier->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
