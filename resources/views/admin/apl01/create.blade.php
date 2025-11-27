@extends('layouts.admin')

@section('title', 'Create APL-01 Form')

@php
    $active = 'apl01';
@endphp

@section('page_title', 'Create New APL-01 Form')
@section('page_description', 'Add a new APL-01 certification application form')

@section('content')
    <form action="{{ route('admin.apl01.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Form Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Form Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Assessee -->
                        <div class="md:col-span-2">
                            <label for="assessee_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessee *</label>
                            <select id="assessee_id" name="assessee_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessee_id') border-red-500 @enderror">
                                <option value="">Select Assessee</option>
                                @foreach($assessees as $assessee)
                                    <option value="{{ $assessee->id }}" {{ old('assessee_id', $selectedAssessee?->id) == $assessee->id ? 'selected' : '' }}>
                                        {{ $assessee->full_name }} ({{ $assessee->registration_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assessee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheme -->
                        <div class="md:col-span-2">
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

                        <!-- Event (Optional) -->
                        <div class="md:col-span-2">
                            <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">Event (Optional)</label>
                            <select id="event_id" name="event_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_id') border-red-500 @enderror">
                                <option value="">Not associated with any event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }} ({{ $event->start_date->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('full_name') border-red-500 @enderror">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div>
                            <label for="id_number" class="block text-sm font-semibold text-gray-700 mb-2">ID Number (NIK/Passport) *</label>
                            <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('id_number') border-red-500 @enderror">
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place of Birth -->
                        <div>
                            <label for="place_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Place of Birth</label>
                            <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('place_of_birth') border-red-500 @enderror">
                            @error('place_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                            <select id="gender" name="gender"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">Nationality</label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality', 'Indonesia') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('nationality') border-red-500 @enderror">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">Mobile Phone *</label>
                            <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('mobile') border-red-500 @enderror">
                            @error('mobile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone (Optional)</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('province') border-red-500 @enderror">
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('postal_code') border-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Current Company -->
                        <div class="md:col-span-2">
                            <label for="current_company" class="block text-sm font-semibold text-gray-700 mb-2">Current Company</label>
                            <input type="text" id="current_company" name="current_company" value="{{ old('current_company') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_company') border-red-500 @enderror">
                            @error('current_company')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Position -->
                        <div>
                            <label for="current_position" class="block text-sm font-semibold text-gray-700 mb-2">Current Position</label>
                            <input type="text" id="current_position" name="current_position" value="{{ old('current_position') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_position') border-red-500 @enderror">
                            @error('current_position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Industry -->
                        <div>
                            <label for="current_industry" class="block text-sm font-semibold text-gray-700 mb-2">Industry</label>
                            <input type="text" id="current_industry" name="current_industry" value="{{ old('current_industry') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_industry') border-red-500 @enderror">
                            @error('current_industry')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certification Purpose -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Information</h3>

                    <div class="space-y-4">
                        <!-- Certification Purpose -->
                        <div>
                            <label for="certification_purpose" class="block text-sm font-semibold text-gray-700 mb-2">Certification Purpose</label>
                            <textarea id="certification_purpose" name="certification_purpose" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certification_purpose') border-red-500 @enderror">{{ old('certification_purpose') }}</textarea>
                            @error('certification_purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Competency -->
                        <div>
                            <label for="target_competency" class="block text-sm font-semibold text-gray-700 mb-2">Target Competency</label>
                            <textarea id="target_competency" name="target_competency" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('target_competency') border-red-500 @enderror">{{ old('target_competency') }}</textarea>
                            @error('target_competency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dynamic Form Fields Container -->
                <div id="dynamic-fields-container">
                    <!-- Dynamic fields will be loaded here via JavaScript -->
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Create Form</span>
                        </button>

                        <a href="{{ route('admin.apl01.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Form number will be auto-generated</li>
                            <li>• Form will be created in Draft status</li>
                            <li>• You can fill in more details after creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Auto-fill data when assessee is selected
        document.getElementById('assessee_id').addEventListener('change', function() {
            if (this.value) {
                // Call autofill API
                fetch('{{ route('admin.apl01.autofill') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        assessee_id: this.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fill form fields
                        Object.keys(data.data).forEach(key => {
                            const field = document.getElementById(key);
                            if (field && data.data[key]) {
                                field.value = data.data[key];
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Load dynamic fields when scheme is selected
        document.getElementById('scheme_id').addEventListener('change', function() {
            loadDynamicFields(this.value);
        });

        function loadDynamicFields(schemeId) {
            const container = document.getElementById('dynamic-fields-container');

            if (!schemeId) {
                container.innerHTML = '';
                return;
            }

            // Show loading
            container.innerHTML = '<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"><div class="flex items-center gap-2 text-gray-500"><span class="material-symbols-outlined animate-spin">progress_activity</span><span>Memuat field tambahan...</span></div></div>';

            fetch('{{ route('admin.apl01.scheme-fields') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    scheme_id: schemeId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.fields.length > 0) {
                    container.innerHTML = renderDynamicFields(data.fields);
                } else {
                    container.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error loading dynamic fields:', error);
                container.innerHTML = '';
            });
        }

        function renderDynamicFields(fields) {
            let html = `
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">dynamic_form</span>
                        Informasi Tambahan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            `;

            fields.forEach(field => {
                const fieldName = 'dynamic_field_' + field.id;
                const required = field.is_required ? '<span class="text-red-500">*</span>' : '';
                const requiredAttr = field.is_required ? 'required' : '';
                const value = field.answer || field.default_value || '';

                switch(field.field_type) {
                    case 'textarea':
                        html += `
                            <div class="md:col-span-2">
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <textarea id="${fieldName}" name="${fieldName}" rows="3" placeholder="${field.placeholder || ''}" ${requiredAttr}
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">${value}</textarea>
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'select':
                        let options = '<option value="">Pilih...</option>';
                        if (field.field_options) {
                            field.field_options.forEach(opt => {
                                const optValue = typeof opt === 'object' ? (opt.value || opt) : opt;
                                const optLabel = typeof opt === 'object' ? (opt.label || opt) : opt;
                                const selected = value == optValue ? 'selected' : '';
                                options += `<option value="${optValue}" ${selected}>${optLabel}</option>`;
                            });
                        }
                        html += `
                            <div>
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <select id="${fieldName}" name="${fieldName}" ${requiredAttr}
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    ${options}
                                </select>
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'radio':
                        let radioOptions = '';
                        if (field.field_options) {
                            field.field_options.forEach(opt => {
                                const optValue = typeof opt === 'object' ? (opt.value || opt) : opt;
                                const optLabel = typeof opt === 'object' ? (opt.label || opt) : opt;
                                const checked = value == optValue ? 'checked' : '';
                                radioOptions += `
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="${fieldName}" value="${optValue}" ${checked} ${requiredAttr}
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">${optLabel}</span>
                                    </label>
                                `;
                            });
                        }
                        html += `
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <div class="flex flex-wrap gap-4">${radioOptions}</div>
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'yesno':
                        html += `
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="${fieldName}" value="yes" ${value === 'yes' ? 'checked' : ''} ${requiredAttr}
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="${fieldName}" value="no" ${value === 'no' ? 'checked' : ''}
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Tidak</span>
                                    </label>
                                </div>
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'date':
                        html += `
                            <div>
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <input type="date" id="${fieldName}" name="${fieldName}" value="${value}" ${requiredAttr}
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'number':
                        html += `
                            <div>
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <input type="number" id="${fieldName}" name="${fieldName}" value="${value}" placeholder="${field.placeholder || ''}" ${requiredAttr}
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    case 'file':
                        html += `
                            <div>
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <input type="file" id="${fieldName}" name="${fieldName}" ${requiredAttr}
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                        break;

                    default: // text, email, url, phone
                        const inputType = ['email', 'url'].includes(field.field_type) ? field.field_type : 'text';
                        html += `
                            <div>
                                <label for="${fieldName}" class="block text-sm font-semibold text-gray-700 mb-2">${field.field_label} ${required}</label>
                                <input type="${inputType}" id="${fieldName}" name="${fieldName}" value="${value}" placeholder="${field.placeholder || ''}" ${requiredAttr}
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                ${field.help_text ? `<p class="mt-1 text-xs text-gray-500">${field.help_text}</p>` : ''}
                            </div>
                        `;
                }
            });

            html += `
                    </div>
                </div>
            `;

            return html;
        }

        // Load fields on page load if scheme is already selected
        const schemeSelect = document.getElementById('scheme_id');
        if (schemeSelect.value) {
            loadDynamicFields(schemeSelect.value);
        }
    </script>
@endsection
