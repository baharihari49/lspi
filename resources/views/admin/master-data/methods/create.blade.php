@extends('layouts.admin')

@section('title', 'Create Method')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Create New Method')
@section('page_description', 'Add a new method')

@section('content')
    <form action="{{ route('admin.master-methods.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Method Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Method Information</h3>

                    <!-- Category -->
                    <div class="mb-4">
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <select id="category" name="category" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                            <option value="__new__" {{ old('category') == '__new__' ? 'selected' : '' }}>+ Add New Category</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Category Input (hidden by default) -->
                    <div id="newCategoryInput" class="mb-4 hidden">
                        <label for="new_category" class="block text-sm font-semibold text-gray-700 mb-2">New Category Name *</label>
                        <input type="text" id="new_category" name="new_category" value="{{ old('new_category') }}"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Code -->
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code *</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('code') border-red-500 @enderror"
                            placeholder="e.g. observation, interview, bank-transfer">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use lowercase with hyphens (e.g. credit-card)</p>
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                            placeholder="e.g. Observation, Interview, Bank Transfer">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Save Method</span>
                        </button>
                        <a href="{{ route('admin.master-methods.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- Category Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Method Categories</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• <strong>assessment</strong> - Assessment methods (observation, interview, portfolio, test, demonstration)</li>
                        <li>• <strong>evidence</strong> - Evidence collection methods (direct, indirect, supplementary)</li>
                        <li>• <strong>payment</strong> - Payment methods (bank transfer, e-wallet, virtual account, credit card)</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Show/hide new category input
        document.getElementById('category').addEventListener('change', function() {
            const newCategoryInput = document.getElementById('newCategoryInput');
            if (this.value === '__new__') {
                newCategoryInput.classList.remove('hidden');
            } else {
                newCategoryInput.classList.add('hidden');
            }
        });
    </script>
@endsection
