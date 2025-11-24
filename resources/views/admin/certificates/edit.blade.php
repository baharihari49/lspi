@extends('layouts.admin')

@section('title', 'Edit Certificate')

@php
    $active = 'certificates';
@endphp

@section('page_title', 'Edit Certificate')
@section('page_description', 'Update certificate information')

@section('content')

@if($certificate->status === 'revoked')
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600">warning</span>
            <div>
                <p class="font-medium text-red-900">This certificate has been revoked</p>
                <p class="text-sm text-red-700 mt-1">Revoked certificates cannot be edited</p>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('admin.certificates.update', $certificate) }}" method="POST" class="w-full">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Certificate Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Details</h3>

                <div class="space-y-4">
                    <!-- Certificate Number (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Certificate Number
                        </label>
                        <input type="text" value="{{ $certificate->certificate_number }}" readonly class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                        <p class="mt-1 text-xs text-gray-500">Certificate number cannot be changed</p>
                    </div>

                    <!-- Registration Number (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Registration Number
                        </label>
                        <input type="text" value="{{ $certificate->registration_number }}" readonly class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                        <p class="mt-1 text-xs text-gray-500">Registration number cannot be changed</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Valid From -->
                        <div>
                            <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-2">
                                Valid From <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from', $certificate->valid_from->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valid_from') border-red-500 @enderror">
                            @error('valid_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valid Until -->
                        <div>
                            <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">
                                Valid Until <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $certificate->valid_until->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valid_until') border-red-500 @enderror">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Language -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                                Certificate Language <span class="text-red-500">*</span>
                            </label>
                            <select name="language" id="language" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('language') border-red-500 @enderror">
                                <option value="id" {{ old('language', $certificate->language) === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ old('language', $certificate->language) === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Public -->
                        <div>
                            <label for="is_public" class="block text-sm font-medium text-gray-700 mb-2">
                                Visibility <span class="text-red-500">*</span>
                            </label>
                            <select name="is_public" id="is_public" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('is_public') border-red-500 @enderror">
                                <option value="1" {{ old('is_public', $certificate->is_public) == 1 ? 'selected' : '' }}>Public</option>
                                <option value="0" {{ old('is_public', $certificate->is_public) == 0 ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('is_public')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Template Name -->
                    <div>
                        <label for="template_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Name
                        </label>
                        <input type="text" name="template_name" id="template_name" value="{{ old('template_name', $certificate->template_name) }}" placeholder="default" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('template_name') border-red-500 @enderror">
                        @error('template_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave as 'default' for standard template</p>
                    </div>

                    <!-- Special Conditions -->
                    <div>
                        <label for="special_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Conditions
                        </label>
                        <textarea name="special_conditions" id="special_conditions" rows="3" placeholder="Any special conditions or restrictions..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('special_conditions') border-red-500 @enderror">{{ old('special_conditions', $certificate->special_conditions) }}</textarea>
                        @error('special_conditions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Will be printed on certificate if specified</p>
                    </div>
                </div>
            </div>

            <!-- Holder Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Holder</h3>

                <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="holder_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="holder_name" id="holder_name" value="{{ old('holder_name', $certificate->holder_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('holder_name') border-red-500 @enderror">
                        @error('holder_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Number -->
                    <div>
                        <label for="holder_id_number" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Number (NIK/KTP)
                        </label>
                        <input type="text" name="holder_id_number" id="holder_id_number" value="{{ old('holder_id_number', $certificate->holder_id_number) }}" maxlength="16" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('holder_id_number') border-red-500 @enderror">
                        @error('holder_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Optional: National ID number</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Notes -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                <div class="space-y-3">
                    @if($certificate->status !== 'revoked')
                        <button type="submit" class="w-full px-6 py-3 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            <span>Save Changes</span>
                        </button>
                    @endif

                    <a href="{{ route('admin.certificates.show', $certificate) }}" class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">close</span>
                        <span>Cancel</span>
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Information</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">info</span>
                            <span>Only editable fields are shown</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">history</span>
                            <span>All changes will be logged</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">block</span>
                            <span>Revoked certificates cannot be edited</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Internal Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Internal Notes</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" id="notes" rows="4" placeholder="Add any internal notes..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $certificate->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">For internal use only, not printed on certificate</p>
                </div>
            </div>

            <!-- Certificate Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Assessee</p>
                        <p class="font-medium text-gray-900">{{ $certificate->assessee->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Scheme</p>
                        <p class="font-medium text-gray-900">{{ $certificate->scheme->code ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        @php
                            $statusConfig = [
                                'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Active'],
                                'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Expired'],
                                'revoked' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Revoked'],
                                'suspended' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Suspended'],
                            ];
                            $config = $statusConfig[$certificate->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $certificate->status];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            {{ $config['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
