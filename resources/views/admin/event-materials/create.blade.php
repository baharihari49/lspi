@extends('layouts.admin')

@section('title', 'Upload Material')

@php
    $active = 'events';
@endphp

@section('page_title', 'Upload Material')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <form action="{{ route('admin.events.materials.store', $event) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Material Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Material Information</h3>

                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror"
                                placeholder="e.g., Module 1 - Introduction to Electronic Journals">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Material Type -->
                        <div>
                            <label for="material_type" class="block text-sm font-semibold text-gray-700 mb-2">Material Type *</label>
                            <select id="material_type" name="material_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('material_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="presentation" {{ old('material_type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="handout" {{ old('material_type') == 'handout' ? 'selected' : '' }}>Handout</option>
                                <option value="video" {{ old('material_type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="document" {{ old('material_type') == 'document' ? 'selected' : '' }}>Document</option>
                                <option value="other" {{ old('material_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('material_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Selection (Optional) -->
                        <div>
                            <label for="event_session_id" class="block text-sm font-semibold text-gray-700 mb-2">Specific Session (Optional)</label>
                            <select id="event_session_id" name="event_session_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_session_id') border-red-500 @enderror">
                                <option value="">All sessions</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('event_session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} - {{ $session->session_date->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_session_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank to make available for all sessions</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Material description...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('order') border-red-500 @enderror"
                                placeholder="0">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">File Upload</h3>

                    <div class="space-y-4">
                        <!-- File -->
                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">File *</label>
                            <input type="file" id="file" name="file" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('file') border-red-500 @enderror">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maximum file size: 50MB</p>
                        </div>

                        <!-- Is Public -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Public Material</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">Allow public access to this material</p>
                        </div>

                        <!-- Is Downloadable -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_downloadable" value="1" {{ old('is_downloadable', true) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Downloadable</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">Allow users to download this material</p>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Upload Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Use descriptive titles</li>
                                    <li>• Max file size: 50MB</li>
                                    <li>• Specify material type</li>
                                    <li>• Set appropriate permissions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">upload</span>
                                <span>Upload Material</span>
                            </button>
                            <a href="{{ route('admin.events.materials.index', $event) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Type Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Material Type Guide</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Presentation:</span>
                                    <span class="block text-xs">Slides, PowerPoint files</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Handout:</span>
                                    <span class="block text-xs">Reference materials, PDFs</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Video:</span>
                                    <span class="block text-xs">Video recordings, tutorials</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Document:</span>
                                    <span class="block text-xs">Text documents, guidelines</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
