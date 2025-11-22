@extends('layouts.admin')

@section('title', 'Edit TUK Facility')

@php
    $active = 'tuk-facilities';
@endphp

@section('page_title', 'Edit TUK Facility')
@section('page_description', 'Update facility or equipment information')

@section('content')
    <form action="{{ route('admin.tuk-facilities.update', $tukFacility) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">TUK *</label>
                            <select id="tuk_id" name="tuk_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ (old('tuk_id', $tukFacility->tuk_id) == $tuk->id) ? 'selected' : '' }}>
                                        {{ $tuk->name }} ({{ $tuk->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facility Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Facility Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $tukFacility->name) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="e.g., Desktop Computer, Projector, Conference Table">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                            <select id="category" name="category" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('category') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                <option value="equipment" {{ old('category', $tukFacility->category) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="furniture" {{ old('category', $tukFacility->category) == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="technology" {{ old('category', $tukFacility->category) == 'technology' ? 'selected' : '' }}>Technology</option>
                                <option value="safety" {{ old('category', $tukFacility->category) == 'safety' ? 'selected' : '' }}>Safety</option>
                                <option value="other" {{ old('category', $tukFacility->category) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Detailed description of the facility or equipment">{{ old('description', $tukFacility->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity & Condition -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $tukFacility->quantity) }}" min="1" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('quantity') border-red-500 @enderror"
                                    placeholder="Number of items">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="condition" class="block text-sm font-semibold text-gray-700 mb-2">Condition *</label>
                                <select id="condition" name="condition" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('condition') border-red-500 @enderror">
                                    <option value="excellent" {{ old('condition', $tukFacility->condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ old('condition', $tukFacility->condition) == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('condition', $tukFacility->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('condition', $tukFacility->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                                @error('condition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Maintenance Information</h3>

                    <div class="space-y-4">
                        <!-- Maintenance Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="last_maintenance" class="block text-sm font-semibold text-gray-700 mb-2">Last Maintenance</label>
                                <input type="date" id="last_maintenance" name="last_maintenance" value="{{ old('last_maintenance', $tukFacility->last_maintenance?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('last_maintenance') border-red-500 @enderror">
                                @error('last_maintenance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="next_maintenance" class="block text-sm font-semibold text-gray-700 mb-2">Next Maintenance</label>
                                <input type="date" id="next_maintenance" name="next_maintenance" value="{{ old('next_maintenance', $tukFacility->next_maintenance?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('next_maintenance') border-red-500 @enderror">
                                @error('next_maintenance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after last maintenance date</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Additional notes, warranty info, or maintenance history">{{ old('notes', $tukFacility->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Current Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Current Status</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">TUK</p>
                                <p class="font-semibold text-gray-900">{{ $tukFacility->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $tukFacility->tuk->code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Category</p>
                                @php
                                    $categoryColors = [
                                        'technology' => 'bg-blue-100 text-blue-700',
                                        'equipment' => 'bg-green-100 text-green-700',
                                        'furniture' => 'bg-yellow-100 text-yellow-700',
                                        'safety' => 'bg-red-100 text-red-700',
                                        'other' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $categoryColors[$tukFacility->category] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($tukFacility->category) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Condition</p>
                                @php
                                    $conditionColors = [
                                        'excellent' => 'bg-green-100 text-green-700',
                                        'good' => 'bg-blue-100 text-blue-700',
                                        'fair' => 'bg-yellow-100 text-yellow-700',
                                        'poor' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $conditionColors[$tukFacility->condition] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($tukFacility->condition) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                                <p class="text-sm text-gray-900">{{ $tukFacility->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Help Information -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Update Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Verify all information</li>
                                    <li>• Update condition regularly</li>
                                    <li>• Track maintenance dates</li>
                                    <li>• Keep notes current</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Facility</span>
                            </button>
                            <a href="{{ route('admin.tuk-facilities.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">{{ $tukFacility->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-semibold text-gray-900">{{ $tukFacility->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
