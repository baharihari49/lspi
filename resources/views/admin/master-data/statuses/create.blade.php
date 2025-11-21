@extends('layouts.admin')

@section('title', 'Create Status')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Create New Status')
@section('page_description', 'Add a new system status')

@section('content')
    <form action="{{ route('admin.master-statuses.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Status Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status Information</h3>

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
                            placeholder="e.g. active, pending, completed">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use lowercase with hyphens (e.g. in-progress)</p>
                    </div>

                    <!-- Label -->
                    <div class="mb-4">
                        <label for="label" class="block text-sm font-semibold text-gray-700 mb-2">Label *</label>
                        <input type="text" id="label" name="label" value="{{ old('label') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('label') border-red-500 @enderror"
                            placeholder="e.g. Active, Pending Approval, Completed">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first in listings</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Save Status</span>
                        </button>
                        <a href="{{ route('admin.master-statuses.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- Category Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Status Categories</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• <strong>user</strong> - User account statuses</li>
                        <li>• <strong>assessment</strong> - Assessment progress</li>
                        <li>• <strong>apl</strong> - APL form statuses</li>
                        <li>• <strong>certificate</strong> - Certificate states</li>
                        <li>• <strong>payment</strong> - Payment statuses</li>
                        <li>• <strong>event</strong> - Event statuses</li>
                        <li>• <strong>document</strong> - Document states</li>
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
