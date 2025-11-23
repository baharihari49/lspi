@extends('layouts.admin')

@section('title', 'Add Education')

@php
    $active = 'assessees';
@endphp

@section('page_title', 'Add Education for ' . $assessee->full_name)
@section('page_description', 'Add education history')

@section('content')
    <form action="{{ route('admin.assessees.education.store', $assessee) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Education Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="institution_name" class="block text-sm font-semibold text-gray-700 mb-2">Institution Name *</label>
                            <input type="text" id="institution_name" name="institution_name" value="{{ old('institution_name') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('institution_name') border-red-500 @enderror">
                            @error('institution_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="institution_location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="institution_location" name="institution_location" value="{{ old('institution_location') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('institution_location') border-red-500 @enderror">
                            @error('institution_location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="institution_type" class="block text-sm font-semibold text-gray-700 mb-2">Institution Type</label>
                            <input type="text" id="institution_type" name="institution_type" value="{{ old('institution_type') }}" placeholder="e.g., University, School"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('institution_type') border-red-500 @enderror">
                            @error('institution_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="education_level" class="block text-sm font-semibold text-gray-700 mb-2">Education Level *</label>
                            <select id="education_level" name="education_level" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('education_level') border-red-500 @enderror">
                                <option value="">Select Level</option>
                                <option value="sd" {{ old('education_level') === 'sd' ? 'selected' : '' }}>SD (Elementary)</option>
                                <option value="smp" {{ old('education_level') === 'smp' ? 'selected' : '' }}>SMP (Junior High)</option>
                                <option value="sma" {{ old('education_level') === 'sma' ? 'selected' : '' }}>SMA (Senior High)</option>
                                <option value="diploma" {{ old('education_level') === 'diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="s1" {{ old('education_level') === 's1' ? 'selected' : '' }}>S1 (Bachelor)</option>
                                <option value="s2" {{ old('education_level') === 's2' ? 'selected' : '' }}>S2 (Master)</option>
                                <option value="s3" {{ old('education_level') === 's3' ? 'selected' : '' }}>S3 (Doctorate)</option>
                                <option value="other" {{ old('education_level') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('education_level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="degree_name" class="block text-sm font-semibold text-gray-700 mb-2">Degree Name</label>
                            <input type="text" id="degree_name" name="degree_name" value="{{ old('degree_name') }}" placeholder="e.g., Bachelor of Science"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('degree_name') border-red-500 @enderror">
                            @error('degree_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="major" class="block text-sm font-semibold text-gray-700 mb-2">Major</label>
                            <input type="text" id="major" name="major" value="{{ old('major') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('major') border-red-500 @enderror">
                            @error('major') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="minor" class="block text-sm font-semibold text-gray-700 mb-2">Minor</label>
                            <input type="text" id="minor" name="minor" value="{{ old('minor') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('minor') border-red-500 @enderror">
                            @error('minor') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_date') border-red-500 @enderror">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_date') border-red-500 @enderror">
                            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="graduation_date" class="block text-sm font-semibold text-gray-700 mb-2">Graduation Date</label>
                            <input type="date" id="graduation_date" name="graduation_date" value="{{ old('graduation_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('graduation_date') border-red-500 @enderror">
                            @error('graduation_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Currently Studying</p>
                                    <p class="text-xs text-gray-600">I am currently enrolled in this program</p>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label for="gpa" class="block text-sm font-semibold text-gray-700 mb-2">GPA</label>
                            <input type="text" id="gpa" name="gpa" value="{{ old('gpa') }}" placeholder="e.g., 3.75"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gpa') border-red-500 @enderror">
                            @error('gpa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="gpa_scale" class="block text-sm font-semibold text-gray-700 mb-2">GPA Scale</label>
                            <input type="text" id="gpa_scale" name="gpa_scale" value="{{ old('gpa_scale', '4.0') }}" placeholder="e.g., 4.0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gpa_scale') border-red-500 @enderror">
                            @error('gpa_scale') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="honors" class="block text-sm font-semibold text-gray-700 mb-2">Honors</label>
                            <input type="text" id="honors" name="honors" value="{{ old('honors') }}" placeholder="e.g., Cum Laude, Magna Cum Laude"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('honors') border-red-500 @enderror">
                            @error('honors') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_number') border-red-500 @enderror">
                            @error('certificate_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="achievements" class="block text-sm font-semibold text-gray-700 mb-2">Achievements</label>
                            <textarea id="achievements" name="achievements" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('achievements') border-red-500 @enderror">{{ old('achievements') }}</textarea>
                            @error('achievements') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('order') border-red-500 @enderror">
                            @error('order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all">
                            Add Education
                        </button>
                        <a href="{{ route('admin.assessees.show', $assessee) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>
                </div>

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
