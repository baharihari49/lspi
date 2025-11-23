@extends('layouts.admin')

@section('title', 'APL-01 Form Builder')

@php
    $active = 'apl01-fields';
@endphp

@section('page_title', 'APL-01 Form Builder')
@section('page_description', 'Manage dynamic form fields for APL-01 forms')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Form Fields</h2>
                    <p class="text-sm text-gray-600">Total: {{ $fields->total() }} fields</p>
                </div>
                <a href="{{ route('admin.apl01-fields.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Create Field</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.apl01-fields.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by field name or label..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'scheme_id', 'field_type', 'section', 'is_active']))
                        <a href="{{ route('admin.apl01-fields.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="flex gap-2 flex-wrap">
                    <select name="scheme_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Schemes</option>
                        @foreach($schemes as $scheme)
                            <option value="{{ $scheme->id }}" {{ request('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                {{ $scheme->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="field_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Field Types</option>
                        <option value="text" {{ request('field_type') === 'text' ? 'selected' : '' }}>Text</option>
                        <option value="textarea" {{ request('field_type') === 'textarea' ? 'selected' : '' }}>Textarea</option>
                        <option value="number" {{ request('field_type') === 'number' ? 'selected' : '' }}>Number</option>
                        <option value="email" {{ request('field_type') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="date" {{ request('field_type') === 'date' ? 'selected' : '' }}>Date</option>
                        <option value="select" {{ request('field_type') === 'select' ? 'selected' : '' }}>Select</option>
                        <option value="radio" {{ request('field_type') === 'radio' ? 'selected' : '' }}>Radio</option>
                        <option value="checkbox" {{ request('field_type') === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                        <option value="checkboxes" {{ request('field_type') === 'checkboxes' ? 'selected' : '' }}>Checkboxes</option>
                        <option value="file" {{ request('field_type') === 'file' ? 'selected' : '' }}>File</option>
                        <option value="yesno" {{ request('field_type') === 'yesno' ? 'selected' : '' }}>Yes/No</option>
                        <option value="rating" {{ request('field_type') === 'rating' ? 'selected' : '' }}>Rating</option>
                        <option value="section_header" {{ request('field_type') === 'section_header' ? 'selected' : '' }}>Section Header</option>
                    </select>

                    <select name="section" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Sections</option>
                        <option value="A" {{ request('section') === 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ request('section') === 'B' ? 'selected' : '' }}>Section B</option>
                        <option value="C" {{ request('section') === 'C' ? 'selected' : '' }}>Section C</option>
                        <option value="D" {{ request('section') === 'D' ? 'selected' : '' }}>Section D</option>
                        <option value="E" {{ request('section') === 'E' ? 'selected' : '' }}>Section E</option>
                    </select>

                    <select name="is_active" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Field</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Required</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($fields as $field)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $field->field_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $field->field_name }}</p>
                                    @if($field->field_description)
                                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($field->field_description, 50) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $field->scheme->name }}</p>
                                <p class="text-xs text-gray-500">{{ $field->scheme->code }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($field->section)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $field->section }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $field->field_type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ $field->order }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($field->is_required)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Required
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Optional</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($field->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.apl01-fields.show', $field) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.apl01-fields.edit', $field) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('admin.apl01-fields.duplicate', $field) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Duplicate">
                                            <span class="material-symbols-outlined text-xl">content_copy</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.apl01-fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this field?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-3">input</span>
                                    <p class="text-gray-500 font-medium">No form fields found</p>
                                    <p class="text-gray-400 text-sm mt-1">Create a new field to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fields->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $fields->links() }}
            </div>
        @endif
    </div>
@endsection
