@extends('layouts.admin')

@section('title', 'Add Criterion')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Add Criterion (KUK)')
@section('page_description', $element->code . ' - ' . $element->title)

@section('content')
    <form action="{{ route('admin.schemes.versions.units.elements.criteria.store', [$scheme, $version, $unit, $element]) }}" method="POST" class="w-full max-w-4xl">
        @csrf

        <div class="space-y-6">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element]) }}" class="hover:text-blue-900">{{ $element->code }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-gray-900 font-semibold">Add KUK</span>
            </nav>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Criterion Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                                placeholder="e.g., 1.1">
                        </div>

                        <div>
                            <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Method</label>
                            <select id="assessment_method" name="assessment_method"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Select Method</option>
                                <option value="written" {{ old('assessment_method') == 'written' ? 'selected' : '' }}>Written</option>
                                <option value="practical" {{ old('assessment_method') == 'practical' ? 'selected' : '' }}>Practical</option>
                                <option value="oral" {{ old('assessment_method') == 'oral' ? 'selected' : '' }}>Oral</option>
                                <option value="portfolio" {{ old('assessment_method') == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="observation" {{ old('assessment_method') == 'observation' ? 'selected' : '' }}>Observation</option>
                            </select>
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (KUK) *</label>
                        <textarea id="description" name="description" rows="3" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('description') border-red-500 @enderror"
                            placeholder="Describe the performance criterion">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="evidence_guide" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Guide</label>
                        <textarea id="evidence_guide" name="evidence_guide" rows="2"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="What evidence is needed to demonstrate competency?">{{ old('evidence_guide') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Create Criterion</span>
                </button>
                <a href="{{ route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
