@props(['fields' => collect(), 'answers' => collect(), 'readonly' => false])

@if($fields->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600">dynamic_form</span>
            Informasi Tambahan
        </h3>

        @php
            $groupedFields = $fields->groupBy('section');
        @endphp

        @foreach($groupedFields as $section => $sectionFields)
            @if($section)
                <div class="mb-4 pb-2 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-700">{{ $section }}</h4>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @foreach($sectionFields as $field)
                    @php
                        $answer = $answers->firstWhere('form_field_id', $field->id);
                        $value = old('dynamic_field_' . $field->id, $answer?->answer_value ?? $field->default_value);
                        $fieldName = 'dynamic_field_' . $field->id;
                        $isRequired = $field->is_required;
                        $fieldWidth = $field->field_width ?? 50;
                        $colSpan = $fieldWidth >= 100 ? 'md:col-span-2' : '';
                    @endphp

                    <div class="{{ $colSpan }}">
                        @switch($field->field_type)
                            @case('section_header')
                                <div class="col-span-2 mt-4 mb-2">
                                    <h4 class="text-md font-semibold text-gray-800">{{ $field->field_label }}</h4>
                                    @if($field->field_description)
                                        <p class="text-sm text-gray-600">{{ $field->field_description }}</p>
                                    @endif
                                </div>
                                @break

                            @case('text')
                            @case('email')
                            @case('url')
                            @case('phone')
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <input
                                        type="{{ $field->field_type === 'email' ? 'email' : ($field->field_type === 'url' ? 'url' : 'text') }}"
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        value="{{ $value }}"
                                        placeholder="{{ $field->placeholder }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'readonly' : '' }}
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('number')
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <input
                                        type="number"
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        value="{{ $value }}"
                                        placeholder="{{ $field->placeholder }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'readonly' : '' }}
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('date')
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <input
                                        type="date"
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        value="{{ $value }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'readonly' : '' }}
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('textarea')
                                <div class="md:col-span-2">
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <textarea
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        rows="3"
                                        placeholder="{{ $field->placeholder }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">{{ $value }}</textarea>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('select')
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <select
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'disabled' : '' }}
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">
                                        <option value="">{{ $field->placeholder ?: 'Pilih...' }}</option>
                                        @foreach($field->field_options ?? [] as $option)
                                            <option value="{{ is_array($option) ? ($option['value'] ?? $option) : $option }}"
                                                {{ $value == (is_array($option) ? ($option['value'] ?? $option) : $option) ? 'selected' : '' }}>
                                                {{ is_array($option) ? ($option['label'] ?? $option) : $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('radio')
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach($field->field_options ?? [] as $option)
                                            @php
                                                $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
                                                $optionLabel = is_array($option) ? ($option['label'] ?? $option) : $option;
                                            @endphp
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="{{ $fieldName }}"
                                                    value="{{ $optionValue }}"
                                                    {{ $value == $optionValue ? 'checked' : '' }}
                                                    {{ $isRequired ? 'required' : '' }}
                                                    {{ $readonly ? 'disabled' : '' }}
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700">{{ $optionLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('checkbox')
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="{{ $fieldName }}"
                                            value="1"
                                            {{ $value ? 'checked' : '' }}
                                            {{ $readonly ? 'disabled' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $field->field_label }}
                                            @if($isRequired) <span class="text-red-500">*</span> @endif
                                        </span>
                                    </label>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('checkboxes')
                                @php
                                    $selectedValues = $answer?->answer_json ?? [];
                                @endphp
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach($field->field_options ?? [] as $option)
                                            @php
                                                $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
                                                $optionLabel = is_array($option) ? ($option['label'] ?? $option) : $option;
                                            @endphp
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="{{ $fieldName }}[]"
                                                    value="{{ $optionValue }}"
                                                    {{ in_array($optionValue, $selectedValues) ? 'checked' : '' }}
                                                    {{ $readonly ? 'disabled' : '' }}
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="text-sm text-gray-700">{{ $optionLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('yesno')
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="{{ $fieldName }}"
                                                value="yes"
                                                {{ $value === 'yes' || $value === '1' ? 'checked' : '' }}
                                                {{ $isRequired ? 'required' : '' }}
                                                {{ $readonly ? 'disabled' : '' }}
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Ya</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="{{ $fieldName }}"
                                                value="no"
                                                {{ $value === 'no' || $value === '0' ? 'checked' : '' }}
                                                {{ $readonly ? 'disabled' : '' }}
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Tidak</span>
                                        </label>
                                    </div>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('rating')
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="{{ $fieldName }}"
                                                    value="{{ $i }}"
                                                    {{ $value == $i ? 'checked' : '' }}
                                                    {{ $isRequired ? 'required' : '' }}
                                                    {{ $readonly ? 'disabled' : '' }}
                                                    class="sr-only peer">
                                                <span class="material-symbols-outlined text-2xl peer-checked:text-yellow-500 text-gray-300 hover:text-yellow-400 transition">star</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @case('file')
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    @if($value && !$readonly)
                                        <div class="mb-2 p-2 bg-gray-50 rounded-lg flex items-center gap-2">
                                            <span class="material-symbols-outlined text-gray-500">attach_file</span>
                                            <span class="text-sm text-gray-600">File sudah diupload</span>
                                            <a href="{{ Storage::url($value) }}" target="_blank" class="text-blue-600 hover:underline text-sm">Lihat</a>
                                        </div>
                                    @endif
                                    @if(!$readonly)
                                        <input
                                            type="file"
                                            id="{{ $fieldName }}"
                                            name="{{ $fieldName }}"
                                            {{ $isRequired && !$value ? 'required' : '' }}
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error($fieldName) border-red-500 @enderror">
                                    @endif
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @break

                            @default
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $field->field_label }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                    <input
                                        type="text"
                                        id="{{ $fieldName }}"
                                        name="{{ $fieldName }}"
                                        value="{{ $value }}"
                                        placeholder="{{ $field->placeholder }}"
                                        {{ $isRequired ? 'required' : '' }}
                                        {{ $readonly ? 'readonly' : '' }}
                                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none {{ $readonly ? 'bg-gray-50' : '' }} @error($fieldName) border-red-500 @enderror">
                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                    @error($fieldName)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                        @endswitch
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endif
