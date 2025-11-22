@extends('layouts.admin')

@section('title', 'Add New Setting')

@php
    $active = 'org-settings';
@endphp

@section('page_title', 'Add New Setting')
@section('page_description', 'Create a new organization configuration setting')

@section('content')
    <form action="{{ route('admin.org-settings.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="space-y-4">
                        <!-- Key -->
                        <div>
                            <label for="key" class="block text-sm font-semibold text-gray-700 mb-2">Setting Key *</label>
                            <input type="text" id="key" name="key" value="{{ old('key') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('key') border-red-500 @enderror"
                                placeholder="e.g., assessment.max_participants">
                            @error('key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Use dot notation for hierarchical keys (e.g., group.subgroup.setting)</p>
                        </div>

                        <!-- Label -->
                        <div>
                            <label for="label" class="block text-sm font-semibold text-gray-700 mb-2">Label</label>
                            <input type="text" id="label" name="label" value="{{ old('label') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('label') border-red-500 @enderror"
                                placeholder="Human-readable label for this setting">
                            @error('label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Data Type *</label>
                            <select id="type" name="type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('type') border-red-500 @enderror">
                                <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String (text)</option>
                                <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean (true/false)</option>
                                <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                                <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>Date</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Group -->
                        <div>
                            <label for="group" class="block text-sm font-semibold text-gray-700 mb-2">Group / Category</label>
                            <input type="text" id="group" name="group" value="{{ old('group') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('group') border-red-500 @enderror"
                                placeholder="e.g., assessment, payment, certificate">
                            @error('group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Used to group related settings together</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Detailed description of what this setting does">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror>
                        </div>
                    </div>
                </div>

                <!-- Value Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Setting Value</h3>

                    <div id="value-container">
                        <!-- String Value -->
                        <div id="value-string" class="value-input">
                            <label for="value_string" class="block text-sm font-semibold text-gray-700 mb-2">String Value</label>
                            <input type="text" id="value_string" name="value" value="{{ old('value') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('value') border-red-500 @enderror"
                                placeholder="Enter text value">
                        </div>

                        <!-- Number Value -->
                        <div id="value-number" class="value-input hidden">
                            <label for="value_number" class="block text-sm font-semibold text-gray-700 mb-2">Number Value</label>
                            <input type="number" id="value_number" name="value" value="{{ old('value') }}" step="any"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('value') border-red-500 @enderror"
                                placeholder="Enter numeric value">
                        </div>

                        <!-- Boolean Value -->
                        <div id="value-boolean" class="value-input hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Boolean Value</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="value" value="1" {{ old('value') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-900 border-gray-300 focus:ring-blue-500">
                                    <span class="text-sm font-semibold text-gray-900">True</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="value" value="0" {{ old('value') == '0' ? 'checked' : 'checked' }}
                                        class="w-4 h-4 text-blue-900 border-gray-300 focus:ring-blue-500">
                                    <span class="text-sm font-semibold text-gray-900">False</span>
                                </label>
                            </div>
                        </div>

                        <!-- JSON Value -->
                        <div id="value-json" class="value-input hidden">
                            <label for="value_json" class="block text-sm font-semibold text-gray-700 mb-2">JSON Value</label>
                            <textarea id="value_json" name="value" rows="6"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('value') border-red-500 @enderror"
                                placeholder='{"key": "value"}'>{{ old('value') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Enter valid JSON format</p>
                        </div>

                        <!-- Date Value -->
                        <div id="value-date" class="value-input hidden">
                            <label for="value_date" class="block text-sm font-semibold text-gray-700 mb-2">Date Value</label>
                            <input type="date" id="value_date" name="value" value="{{ old('value') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('value') border-red-500 @enderror">
                        </div>

                        @error('value')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Visibility & Access</h3>

                    <div class="space-y-4">
                        <!-- Is Public -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Public Setting</p>
                                    <p class="text-xs text-gray-600">Can be viewed by non-admin users (API, frontend)</p>
                                </div>
                            </label>
                        </div>

                        <!-- Is Editable -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_editable" value="1" {{ old('is_editable', true) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Editable Setting</p>
                                    <p class="text-xs text-gray-600">Can be modified via admin UI (locked settings can only be changed via code)</p>
                                </div>
                            </label>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Setting Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Use clear, descriptive keys</li>
                                    <li>• Choose appropriate data type</li>
                                    <li>• Group related settings</li>
                                    <li>• Provide helpful descriptions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Create Setting</span>
                            </button>
                            <a href="{{ route('admin.org-settings.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Dynamic value input switching based on type
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const valueInputs = document.querySelectorAll('.value-input');

            // Hide all value inputs
            valueInputs.forEach(input => {
                input.classList.add('hidden');
                // Disable inputs in hidden sections
                const inputFields = input.querySelectorAll('input, textarea');
                inputFields.forEach(field => field.disabled = true);
            });

            // Show the selected type's input
            const selectedInput = document.getElementById('value-' + type);
            if (selectedInput) {
                selectedInput.classList.remove('hidden');
                // Enable inputs in visible section
                const inputFields = selectedInput.querySelectorAll('input, textarea');
                inputFields.forEach(field => field.disabled = false);
            }
        });

        // Trigger on page load to set correct state
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
    @endpush
@endsection
