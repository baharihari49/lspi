@extends('layouts.admin')

@section('title', 'Add Experience')

@php
    $active = 'assessees';
@endphp

@section('page_title', 'Add Experience for ' . $assessee->full_name)
@section('page_description', 'Add new experience to assessee portfolio')

@section('content')
    <form action="{{ route('admin.assessees.experience.store', $assessee) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Fields -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Experience Type -->
                        <div class="md:col-span-2">
                            <label for="experience_type" class="block text-sm font-semibold text-gray-700 mb-2">Experience Type *</label>
                            <select id="experience_type" name="experience_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('experience_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="professional" {{ old('experience_type') === 'professional' ? 'selected' : '' }}>Professional</option>
                                <option value="project" {{ old('experience_type') === 'project' ? 'selected' : '' }}>Project</option>
                                <option value="volunteer" {{ old('experience_type') === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                                <option value="certification" {{ old('experience_type') === 'certification' ? 'selected' : '' }}>Certification</option>
                                <option value="training" {{ old('experience_type') === 'training' ? 'selected' : '' }}>Training</option>
                                <option value="publication" {{ old('experience_type') === 'publication' ? 'selected' : '' }}>Publication</option>
                                <option value="award" {{ old('experience_type') === 'award' ? 'selected' : '' }}>Award</option>
                                <option value="other" {{ old('experience_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('experience_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Organization -->
                        <div class="md:col-span-2">
                            <label for="organization" class="block text-sm font-semibold text-gray-700 mb-2">Organization</label>
                            <input type="text" id="organization" name="organization" value="{{ old('organization') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('organization') border-red-500 @enderror">
                            @error('organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Ongoing -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_ongoing" value="1" {{ old('is_ongoing') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Ongoing</p>
                                    <p class="text-xs text-gray-600">This experience is currently ongoing</p>
                                </div>
                            </label>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Responsibilities -->
                        <div class="md:col-span-2">
                            <label for="responsibilities" class="block text-sm font-semibold text-gray-700 mb-2">Responsibilities</label>
                            <textarea id="responsibilities" name="responsibilities" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('responsibilities') border-red-500 @enderror">{{ old('responsibilities') }}</textarea>
                            @error('responsibilities')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Outcomes -->
                        <div class="md:col-span-2">
                            <label for="outcomes" class="block text-sm font-semibold text-gray-700 mb-2">Outcomes</label>
                            <textarea id="outcomes" name="outcomes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('outcomes') border-red-500 @enderror">{{ old('outcomes') }}</textarea>
                            @error('outcomes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skills Gained -->
                        <div class="md:col-span-2">
                            <label for="skills_gained" class="block text-sm font-semibold text-gray-700 mb-2">Skills Gained</label>
                            <textarea id="skills_gained" name="skills_gained" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('skills_gained') border-red-500 @enderror">{{ old('skills_gained') }}</textarea>
                            @error('skills_gained')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Type-Specific Fields -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- For Certifications -->
                        <div class="md:col-span-2">
                            <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_number') border-red-500 @enderror">
                            @error('certificate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="issuing_organization" class="block text-sm font-semibold text-gray-700 mb-2">Issuing Organization</label>
                            <input type="text" id="issuing_organization" name="issuing_organization" value="{{ old('issuing_organization') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issuing_organization') border-red-500 @enderror">
                            @error('issuing_organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="issue_date" class="block text-sm font-semibold text-gray-700 mb-2">Issue Date</label>
                            <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issue_date') border-red-500 @enderror">
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('expiry_date') border-red-500 @enderror">
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- For Publications -->
                        <div class="md:col-span-2">
                            <label for="publication_title" class="block text-sm font-semibold text-gray-700 mb-2">Publication Title</label>
                            <input type="text" id="publication_title" name="publication_title" value="{{ old('publication_title') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('publication_title') border-red-500 @enderror">
                            @error('publication_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="publisher" class="block text-sm font-semibold text-gray-700 mb-2">Publisher</label>
                            <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('publisher') border-red-500 @enderror">
                            @error('publisher')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="publication_date" class="block text-sm font-semibold text-gray-700 mb-2">Publication Date</label>
                            <input type="date" id="publication_date" name="publication_date" value="{{ old('publication_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('publication_date') border-red-500 @enderror">
                            @error('publication_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="publication_url" class="block text-sm font-semibold text-gray-700 mb-2">Publication URL</label>
                            <input type="url" id="publication_url" name="publication_url" value="{{ old('publication_url') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('publication_url') border-red-500 @enderror">
                            @error('publication_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="doi" class="block text-sm font-semibold text-gray-700 mb-2">DOI</label>
                            <input type="text" id="doi" name="doi" value="{{ old('doi') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('doi') border-red-500 @enderror">
                            @error('doi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- For Awards -->
                        <div class="md:col-span-2">
                            <label for="award_name" class="block text-sm font-semibold text-gray-700 mb-2">Award Name</label>
                            <input type="text" id="award_name" name="award_name" value="{{ old('award_name') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('award_name') border-red-500 @enderror">
                            @error('award_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="award_issuer" class="block text-sm font-semibold text-gray-700 mb-2">Award Issuer</label>
                            <input type="text" id="award_issuer" name="award_issuer" value="{{ old('award_issuer') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('award_issuer') border-red-500 @enderror">
                            @error('award_issuer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="award_date" class="block text-sm font-semibold text-gray-700 mb-2">Award Date</label>
                            <input type="date" id="award_date" name="award_date" value="{{ old('award_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('award_date') border-red-500 @enderror">
                            @error('award_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Documentation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Documentation</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Evidence File -->
                        <div class="md:col-span-2">
                            <label for="evidence_file" class="block text-sm font-semibold text-gray-700 mb-2">Evidence File</label>
                            <input type="file" id="evidence_file" name="evidence_file"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_file') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Max size: 10MB. Formats: PDF, DOC, DOCX, JPG, PNG</p>
                            @error('evidence_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference URL -->
                        <div class="md:col-span-2">
                            <label for="reference_url" class="block text-sm font-semibold text-gray-700 mb-2">Reference URL</label>
                            <input type="url" id="reference_url" name="reference_url" value="{{ old('reference_url') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('reference_url') border-red-500 @enderror">
                            @error('reference_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Relevance to Certification -->
                        <div class="md:col-span-2">
                            <label for="relevance_to_certification" class="block text-sm font-semibold text-gray-700 mb-2">Relevance to Certification</label>
                            <textarea id="relevance_to_certification" name="relevance_to_certification" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('relevance_to_certification') border-red-500 @enderror">{{ old('relevance_to_certification') }}</textarea>
                            @error('relevance_to_certification')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Relevance Score -->
                        <div>
                            <label for="relevance_score" class="block text-sm font-semibold text-gray-700 mb-2">Relevance Score (1-10)</label>
                            <input type="number" id="relevance_score" name="relevance_score" value="{{ old('relevance_score') }}" min="1" max="10"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('relevance_score') border-red-500 @enderror">
                            @error('relevance_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('order') border-red-500 @enderror">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all">
                            Add Experience
                        </button>

                        <a href="{{ route('admin.assessees.show', $assessee) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Note:</span> Fields marked with * are required.
                        </p>
                    </div>
                </div>

                <!-- Assessee Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessee</h3>
                    <div class="flex items-center gap-3">
                        @if($assessee->photo)
                            <img src="{{ asset('storage/' . $assessee->photo) }}" alt="{{ $assessee->full_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($assessee->full_name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $assessee->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $assessee->registration_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
