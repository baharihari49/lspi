@extends('layouts.admin')

@section('title', 'Add Employment')

@php
    $active = 'assessees';
@endphp

@section('page_title', 'Add Employment for ' . $assessee->full_name)
@section('page_description', 'Add employment history')

@section('content')
    <form action="{{ route('admin.assessees.employment.store', $assessee) }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">Company Name *</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('company_name') border-red-500 @enderror">
                            @error('company_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="company_industry" class="block text-sm font-semibold text-gray-700 mb-2">Industry</label>
                            <input type="text" id="company_industry" name="company_industry" value="{{ old('company_industry') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('company_industry') border-red-500 @enderror">
                            @error('company_industry') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="company_location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="company_location" name="company_location" value="{{ old('company_location') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('company_location') border-red-500 @enderror">
                            @error('company_location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="position_title" class="block text-sm font-semibold text-gray-700 mb-2">Position Title *</label>
                            <input type="text" id="position_title" name="position_title" value="{{ old('position_title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('position_title') border-red-500 @enderror">
                            @error('position_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                            <input type="text" id="department" name="department" value="{{ old('department') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('department') border-red-500 @enderror">
                            @error('department') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="employment_type" class="block text-sm font-semibold text-gray-700 mb-2">Employment Type *</label>
                            <select id="employment_type" name="employment_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('employment_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="full_time" {{ old('employment_type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type') === 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type') === 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="internship" {{ old('employment_type') === 'internship' ? 'selected' : '' }}>Internship</option>
                                <option value="freelance" {{ old('employment_type') === 'freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('employment_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Current Employment</p>
                                    <p class="text-xs text-gray-600">I currently work here</p>
                                </div>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <label for="job_description" class="block text-sm font-semibold text-gray-700 mb-2">Job Description</label>
                            <textarea id="job_description" name="job_description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('job_description') border-red-500 @enderror">{{ old('job_description') }}</textarea>
                            @error('job_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="responsibilities" class="block text-sm font-semibold text-gray-700 mb-2">Responsibilities</label>
                            <textarea id="responsibilities" name="responsibilities" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('responsibilities') border-red-500 @enderror">{{ old('responsibilities') }}</textarea>
                            @error('responsibilities') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="achievements" class="block text-sm font-semibold text-gray-700 mb-2">Achievements</label>
                            <textarea id="achievements" name="achievements" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('achievements') border-red-500 @enderror">{{ old('achievements') }}</textarea>
                            @error('achievements') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="skills_used" class="block text-sm font-semibold text-gray-700 mb-2">Skills Used</label>
                            <textarea id="skills_used" name="skills_used" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('skills_used') border-red-500 @enderror">{{ old('skills_used') }}</textarea>
                            @error('skills_used') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="supervisor_name" class="block text-sm font-semibold text-gray-700 mb-2">Supervisor Name</label>
                            <input type="text" id="supervisor_name" name="supervisor_name" value="{{ old('supervisor_name') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('supervisor_name') border-red-500 @enderror">
                            @error('supervisor_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="supervisor_title" class="block text-sm font-semibold text-gray-700 mb-2">Supervisor Title</label>
                            <input type="text" id="supervisor_title" name="supervisor_title" value="{{ old('supervisor_title') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('supervisor_title') border-red-500 @enderror">
                            @error('supervisor_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="supervisor_contact" class="block text-sm font-semibold text-gray-700 mb-2">Supervisor Contact</label>
                            <input type="text" id="supervisor_contact" name="supervisor_contact" value="{{ old('supervisor_contact') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('supervisor_contact') border-red-500 @enderror">
                            @error('supervisor_contact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                            Add Employment
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
