@extends('layouts.admin')

@section('title', 'Form Field Details')

@php
    $active = 'apl01-fields';
@endphp

@section('page_title', $field->field_label)
@section('page_description', 'Form Field Details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Basic Information</h3>
                    <a href="{{ route('admin.apl01-fields.edit', $field) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit Field</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">label</span>
                            <div>
                                <p class="text-xs text-gray-600">Field Label</p>
                                <p class="font-semibold text-gray-900">{{ $field->field_label }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">code</span>
                            <div>
                                <p class="text-xs text-gray-600">Field Name</p>
                                <p class="font-semibold text-gray-900">{{ $field->field_name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">category</span>
                            <div>
                                <p class="text-xs text-gray-600">Field Type</p>
                                <p class="font-semibold text-gray-900">{{ $field->field_type_label }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                            <div>
                                <p class="text-xs text-gray-600">Scheme</p>
                                <p class="font-semibold text-gray-900">{{ $field->scheme->name }}</p>
                            </div>
                        </div>

                        @if($field->section)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">view_module</span>
                                <div>
                                    <p class="text-xs text-gray-600">Section</p>
                                    <p class="font-semibold text-gray-900">{{ $field->section }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">sort</span>
                            <div>
                                <p class="text-xs text-gray-600">Order</p>
                                <p class="font-semibold text-gray-900">{{ $field->order }}</p>
                            </div>
                        </div>

                        @if($field->field_width)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">width</span>
                                <div>
                                    <p class="text-xs text-gray-600">Field Width</p>
                                    <p class="font-semibold text-gray-900">{{ $field->field_width }}/12</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($field->field_description)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Description</p>
                            <p class="text-sm text-gray-700">{{ $field->field_description }}</p>
                        </div>
                    @endif

                    @if($field->placeholder || $field->help_text)
                        <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($field->placeholder)
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Placeholder</p>
                                    <p class="text-sm text-gray-700">{{ $field->placeholder }}</p>
                                </div>
                            @endif

                            @if($field->help_text)
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Help Text</p>
                                    <p class="text-sm text-gray-700">{{ $field->help_text }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Field Configuration -->
            @if($field->field_options || $field->validation_rules || $field->conditional_logic || $field->file_config)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Field Configuration</h3>

                    <div class="space-y-4">
                        @if($field->field_options)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Field Options</p>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($field->field_options, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif

                        @if($field->validation_rules)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Validation Rules</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($field->validation_rules as $rule)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-medium">{{ $rule }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($field->conditional_logic)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">Conditional Logic</p>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($field->conditional_logic, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif

                        @if($field->file_config)
                            <div>
                                <p class="text-xs text-gray-600 mb-2">File Upload Configuration</p>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($field->file_config, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Additional Settings -->
            @if($field->default_value || $field->css_class || $field->wrapper_class)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Settings</h3>

                    <div class="space-y-3">
                        @if($field->default_value)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Default Value</p>
                                <p class="text-sm text-gray-700">{{ $field->default_value }}</p>
                            </div>
                        @endif

                        @if($field->css_class)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">CSS Class</p>
                                <code class="text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $field->css_class }}</code>
                            </div>
                        @endif

                        @if($field->wrapper_class)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Wrapper Class</p>
                                <code class="text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $field->wrapper_class }}</code>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.apl01-fields.edit', $field) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Field
                    </a>

                    <form action="{{ route('admin.apl01-fields.duplicate', $field) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all">
                            Duplicate Field
                        </button>
                    </form>

                    <a href="{{ route('admin.apl01-fields.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    <form action="{{ route('admin.apl01-fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this field?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                            Delete Field
                        </button>
                    </form>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $field->created_at->format('d M Y H:i') }}
                        @if($field->createdBy)
                            <br>by {{ $field->createdBy->name }}
                        @endif
                    </p>
                    @if($field->updated_at != $field->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $field->updated_at->format('d M Y H:i') }}
                            @if($field->updatedBy)
                                <br>by {{ $field->updatedBy->name }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <!-- Field Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Field Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Required</span>
                        @if($field->is_required)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Enabled</span>
                        @if($field->is_enabled)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Visible</span>
                        @if($field->is_visible)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Active</span>
                        @if($field->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Active</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
