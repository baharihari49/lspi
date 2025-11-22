@extends('layouts.admin')

@section('title', 'Add TUK Facility')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add TUK Facility</h1>
        <p class="text-gray-600 mt-2">Add a new facility or equipment to a TUK</p>
    </div>

    <form action="{{ route('admin.tuk-facilities.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-medium text-gray-700 mb-2">TUK <span class="text-red-500">*</span></label>
                            <select id="tuk_id" name="tuk_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
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
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Facility Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" placeholder="e.g., Desktop Computer, Projector">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                            <select id="category" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="technology" {{ old('category') == 'technology' ? 'selected' : '' }}>Technology</option>
                                <option value="safety" {{ old('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Detailed description of the facility">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity & Condition -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
                                <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('condition') border-red-500 @enderror">
                                    <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                                @error('condition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Information</h2>

                    <div class="space-y-4">
                        <!-- Maintenance Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="last_maintenance" class="block text-sm font-medium text-gray-700 mb-2">Last Maintenance</label>
                                <input type="date" id="last_maintenance" name="last_maintenance" value="{{ old('last_maintenance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_maintenance') border-red-500 @enderror">
                                @error('last_maintenance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="next_maintenance" class="block text-sm font-medium text-gray-700 mb-2">Next Maintenance</label>
                                <input type="date" id="next_maintenance" name="next_maintenance" value="{{ old('next_maintenance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('next_maintenance') border-red-500 @enderror">
                                @error('next_maintenance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after last maintenance date</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror" placeholder="Additional notes about the facility">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>

                    <div class="space-y-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Save Facility
                            </span>
                        </button>

                        <a href="{{ route('admin.tuk-facilities.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Tips</h3>
                        <ul class="text-xs text-gray-600 space-y-2">
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Set maintenance dates to track equipment lifecycle</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Update condition regularly during inspections</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Use notes field for warranty or purchase information</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
