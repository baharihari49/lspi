@extends('layouts.admin')

@section('title', 'Edit Unit')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Edit Unit')

@section('content')
    <form action="{{ route('admin.schemes.versions.units.update', [$scheme, $version, $unit]) }}" method="POST" class="w-full max-w-4xl">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-gray-900 font-semibold">Edit {{ $unit->code }}</span>
            </nav>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code *</label>
                            <input type="text" id="code" name="code" value="{{ old('code', $unit->code) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono">
                        </div>

                        <div>
                            <label for="unit_type" class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                            <select id="unit_type" name="unit_type" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="core" {{ old('unit_type', $unit->unit_type) == 'core' ? 'selected' : '' }}>Core</option>
                                <option value="optional" {{ old('unit_type', $unit->unit_type) == 'optional' ? 'selected' : '' }}>Optional</option>
                                <option value="supporting" {{ old('unit_type', $unit->unit_type) == 'supporting' ? 'selected' : '' }}>Supporting</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $unit->title) }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $unit->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="credit_hours" class="block text-sm font-semibold text-gray-700 mb-2">Credit Hours</label>
                            <input type="number" id="credit_hours" name="credit_hours" value="{{ old('credit_hours', $unit->credit_hours) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $unit->order) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', $unit->is_mandatory) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">Mandatory Unit</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Update Unit</span>
                </button>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
