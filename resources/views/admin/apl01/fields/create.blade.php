@extends('layouts.admin')

@section('title', 'Create Form Field')

@php
    $active = 'apl01-fields';
@endphp

@section('page_title', 'Create New Form Field')
@section('page_description', 'Add a new dynamic field to APL-01 form')

@section('content')
    <form action="{{ route('admin.apl01-fields.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="space-y-4">
                        <!-- Scheme -->
                        <div>
                            <label for="scheme_id" class="block text-sm font-semibold text-gray-700 mb-2">Certification Scheme *</label>
                            <select id="scheme_id" name="scheme_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_id') border-red-500 @enderror">
                                <option value="">Select Scheme</option>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}" {{ old('scheme_id', $selectedScheme?->id) == $scheme->id ? 'selected' : '' }}>
                                        {{ $scheme->name }} ({{ $scheme->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('scheme_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Name -->
                        <div>
                            <label for="field_name" class="block text-sm font-semibold text-gray-700 mb-2">Field Name (Unique Identifier) *</label>
                            <input type="text" id="field_name" name="field_name" value="{{ old('field_name') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('field_name') border-red-500 @enderror"
                                placeholder="e.g., years_of_experience">
                            @error('field_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Lowercase, underscores allowed, no spaces</p>
                        </div>

                        <!-- Field Label -->
                        <div>
                            <label for="field_label" class="block text-sm font-semibold text-gray-700 mb-2">Field Label *</label>
                            <input type="text" id="field_label" name="field_label" value="{{ old('field_label') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('field_label') border-red-500 @enderror"
                                placeholder="e.g., How many years of experience do you have?">
                            @error('field_label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Description -->
                        <div>
                            <label for="field_description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                            <textarea id="field_description" name="field_description" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('field_description') border-red-500 @enderror"
                                placeholder="Additional instructions or help text...">{{ old('field_description') }}</textarea>
                            @error('field_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Type -->
                        <div>
                            <label for="field_type" class="block text-sm font-semibold text-gray-700 mb-2">Field Type *</label>
                            <select id="field_type" name="field_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('field_type') border-red-500 @enderror">
                                <option value="">Select Field Type</option>
                                <option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="textarea" {{ old('field_type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="number" {{ old('field_type') == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="email" {{ old('field_type') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="date" {{ old('field_type') == 'date' ? 'selected' : '' }}>Date</option>
                                <option value="select" {{ old('field_type') == 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                                <option value="radio" {{ old('field_type') == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                <option value="checkbox" {{ old('field_type') == 'checkbox' ? 'selected' : '' }}>Checkbox (Single)</option>
                                <option value="checkboxes" {{ old('field_type') == 'checkboxes' ? 'selected' : '' }}>Checkboxes (Multiple)</option>
                                <option value="file" {{ old('field_type') == 'file' ? 'selected' : '' }}>File Upload</option>
                                <option value="image" {{ old('field_type') == 'image' ? 'selected' : '' }}>Image Upload</option>
                                <option value="url" {{ old('field_type') == 'url' ? 'selected' : '' }}>URL</option>
                                <option value="phone" {{ old('field_type') == 'phone' ? 'selected' : '' }}>Phone Number</option>
                                <option value="rating" {{ old('field_type') == 'rating' ? 'selected' : '' }}>Rating (1-5)</option>
                                <option value="yesno" {{ old('field_type') == 'yesno' ? 'selected' : '' }}>Yes/No</option>
                                <option value="section_header" {{ old('field_type') == 'section_header' ? 'selected' : '' }}>Section Header</option>
                                <option value="html" {{ old('field_type') == 'html' ? 'selected' : '' }}>HTML Content</option>
                            </select>
                            @error('field_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Layout & Organization -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Layout & Organization</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Section -->
                        <div>
                            <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                            <input type="text" id="section" name="section" value="{{ old('section') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('section') border-red-500 @enderror"
                                placeholder="e.g., A, B, C">
                            @error('section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('order') border-red-500 @enderror">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Width -->
                        <div>
                            <label for="field_width" class="block text-sm font-semibold text-gray-700 mb-2">Field Width (1-12)</label>
                            <input type="number" id="field_width" name="field_width" value="{{ old('field_width', 12) }}" min="1" max="12"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('field_width') border-red-500 @enderror">
                            @error('field_width')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Placeholder -->
                        <div>
                            <label for="placeholder" class="block text-sm font-semibold text-gray-700 mb-2">Placeholder Text</label>
                            <input type="text" id="placeholder" name="placeholder" value="{{ old('placeholder') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('placeholder') border-red-500 @enderror"
                                placeholder="e.g., Enter your answer here...">
                            @error('placeholder')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Help Text -->
                        <div>
                            <label for="help_text" class="block text-sm font-semibold text-gray-700 mb-2">Help Text</label>
                            <input type="text" id="help_text" name="help_text" value="{{ old('help_text') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('help_text') border-red-500 @enderror"
                                placeholder="Small text shown below the field">
                            @error('help_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="space-y-6">
                <!-- Field Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Field Settings</h3>

                    <div class="space-y-3">
                        <!-- Is Required -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_required" class="ml-2 text-sm font-medium text-gray-700">Required Field</label>
                        </div>

                        <!-- Is Enabled -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ old('is_enabled', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_enabled" class="ml-2 text-sm font-medium text-gray-700">Enabled</label>
                        </div>

                        <!-- Is Visible -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_visible" class="ml-2 text-sm font-medium text-gray-700">Visible</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Create Field</span>
                        </button>

                        <a href="{{ route('admin.apl01-fields.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Field name must be unique per scheme</li>
                            <li>• Use section to group related fields</li>
                            <li>• Order determines field sequence</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
