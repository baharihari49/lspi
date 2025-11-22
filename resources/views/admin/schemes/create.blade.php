@extends('layouts.admin')

@section('title', 'Add Certification Scheme')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Add Certification Scheme')
@section('page_description', 'Create new certification scheme')

@section('content')
    <form action="{{ route('admin.schemes.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="space-y-4">
                        <!-- Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Scheme Code *</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('code') border-red-500 @enderror"
                                placeholder="e.g., SKM-001">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Scheme Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="e.g., Pengelolaan Jurnal Elektronik">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Occupation Title -->
                        <div>
                            <label for="occupation_title" class="block text-sm font-semibold text-gray-700 mb-2">Occupation Title</label>
                            <input type="text" id="occupation_title" name="occupation_title" value="{{ old('occupation_title') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('occupation_title') border-red-500 @enderror"
                                placeholder="e.g., Manajer Jurnal Elektronik">
                            @error('occupation_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Brief description of the scheme">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Classification -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Classification</h3>

                    <div class="space-y-4">
                        <!-- Scheme Type -->
                        <div>
                            <label for="scheme_type" class="block text-sm font-semibold text-gray-700 mb-2">Scheme Type *</label>
                            <select id="scheme_type" name="scheme_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="occupation" {{ old('scheme_type') == 'occupation' ? 'selected' : '' }}>Occupation</option>
                                <option value="cluster" {{ old('scheme_type') == 'cluster' ? 'selected' : '' }}>Cluster</option>
                                <option value="qualification" {{ old('scheme_type') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                            </select>
                            @error('scheme_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KKNI Level -->
                        <div>
                            <label for="qualification_level" class="block text-sm font-semibold text-gray-700 mb-2">KKNI Level</label>
                            <select id="qualification_level" name="qualification_level"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('qualification_level') border-red-500 @enderror">
                                <option value="">Select Level</option>
                                @for($i = 1; $i <= 9; $i++)
                                    @php $level = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'][$i-1]; @endphp
                                    <option value="{{ $level }}" {{ old('qualification_level') == $level ? 'selected' : '' }}>Level {{ $level }}</option>
                                @endfor
                            </select>
                            @error('qualification_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Kerangka Kualifikasi Nasional Indonesia level</p>
                        </div>

                        <!-- Sector -->
                        <div>
                            <label for="sector" class="block text-sm font-semibold text-gray-700 mb-2">Sector</label>
                            <input type="text" id="sector" name="sector" value="{{ old('sector') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('sector') border-red-500 @enderror"
                                placeholder="e.g., Information Technology, Education, Health">
                            @error('sector')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates & Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Dates & Status</h3>

                    <div class="space-y-4">
                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="effective_date" class="block text-sm font-semibold text-gray-700 mb-2">Effective Date</label>
                                <input type="date" id="effective_date" name="effective_date" value="{{ old('effective_date') }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('effective_date') border-red-500 @enderror">
                                @error('effective_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="review_date" class="block text-sm font-semibold text-gray-700 mb-2">Review Date</label>
                                <input type="date" id="review_date" name="review_date" value="{{ old('review_date') }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('review_date') border-red-500 @enderror">
                                @error('review_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after effective date</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status_id" name="status_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Set as Active</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">Uncheck to create as inactive scheme</p>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Scheme Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Fill all required fields (*)</li>
                                    <li>• Use unique scheme code</li>
                                    <li>• Select appropriate KKNI level</li>
                                    <li>• Set effective and review dates</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Create Scheme</span>
                            </button>
                            <a href="{{ route('admin.schemes.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Type Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Scheme Type Guide</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Occupation:</span>
                                    <span class="block text-xs">Job-specific competency</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Cluster:</span>
                                    <span class="block text-xs">Group of related skills</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Qualification:</span>
                                    <span class="block text-xs">Full qualification level</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
