@extends('layouts.admin')

@section('title', 'Add Unit')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Add Competency Unit')
@section('page_description', $scheme->code . ' - Version ' . $version->version)

@section('content')
    <form action="{{ route('admin.schemes.versions.units.store', [$scheme, $version]) }}" method="POST" class="w-full max-w-4xl">
        @csrf

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-gray-900 font-semibold">Add Unit</span>
            </nav>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Unit Code *</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono @error('code') border-red-500 @enderror"
                                placeholder="e.g., J.62.001.01">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_type" class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                            <select id="unit_type" name="unit_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('unit_type') border-red-500 @enderror">
                                <option value="core" {{ old('unit_type') == 'core' ? 'selected' : '' }}>Core</option>
                                <option value="optional" {{ old('unit_type') == 'optional' ? 'selected' : '' }}>Optional</option>
                                <option value="supporting" {{ old('unit_type') == 'supporting' ? 'selected' : '' }}>Supporting</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('title') border-red-500 @enderror"
                            placeholder="e.g., Mengelola Sistem Jurnal Elektronik">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="credit_hours" class="block text-sm font-semibold text-gray-700 mb-2">Credit Hours</label>
                            <input type="number" id="credit_hours" name="credit_hours" value="{{ old('credit_hours') }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('credit_hours') border-red-500 @enderror">
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('order') border-red-500 @enderror">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', true) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">Mandatory Unit</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Create Unit</span>
                </button>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
