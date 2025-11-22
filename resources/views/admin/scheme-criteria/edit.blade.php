@extends('layouts.admin')

@section('title', 'Edit Criterion')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Edit Criterion (KUK)')

@section('content')
    <form action="{{ route('admin.schemes.versions.units.elements.criteria.update', [$scheme, $version, $unit, $element, $criterion]) }}" method="POST" class="w-full max-w-4xl">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code</label>
                            <input type="text" id="code" name="code" value="{{ old('code', $criterion->code) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono">
                        </div>

                        <div>
                            <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Method</label>
                            <select id="assessment_method" name="assessment_method" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Select</option>
                                <option value="written" {{ old('assessment_method', $criterion->assessment_method) == 'written' ? 'selected' : '' }}>Written</option>
                                <option value="practical" {{ old('assessment_method', $criterion->assessment_method) == 'practical' ? 'selected' : '' }}>Practical</option>
                                <option value="oral" {{ old('assessment_method', $criterion->assessment_method) == 'oral' ? 'selected' : '' }}>Oral</option>
                                <option value="portfolio" {{ old('assessment_method', $criterion->assessment_method) == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="observation" {{ old('assessment_method', $criterion->assessment_method) == 'observation' ? 'selected' : '' }}>Observation</option>
                            </select>
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $criterion->order) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (KUK) *</label>
                        <textarea id="description" name="description" rows="3" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $criterion->description) }}</textarea>
                    </div>

                    <div>
                        <label for="evidence_guide" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Guide</label>
                        <textarea id="evidence_guide" name="evidence_guide" rows="2"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('evidence_guide', $criterion->evidence_guide) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Update Criterion</span>
                </button>
                <a href="{{ route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
