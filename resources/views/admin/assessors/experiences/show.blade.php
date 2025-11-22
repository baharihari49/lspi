@extends('layouts.admin')

@section('title', 'Experience Details')

@php
    $active = 'assessor-experiences';
@endphp

@section('page_title', 'Experience Details')
@section('page_description', 'View assessor work experience information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessor Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr($assessorExperience->assessor->full_name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ $assessorExperience->assessor->full_name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $assessorExperience->assessor->registration_number }}</p>
                        @if($assessorExperience->assessor->met_number)
                            <p class="text-sm text-gray-600">MET: {{ $assessorExperience->assessor->met_number }}</p>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('admin.assessors.show', $assessorExperience->assessor) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                                <span class="material-symbols-outlined text-sm">person</span>
                                <span>View Assessor Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experience Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Experience Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">business</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Organization</p>
                                <p class="font-semibold text-gray-900">{{ $assessorExperience->organization_name }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">badge</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Position</p>
                                <p class="font-semibold text-gray-900">{{ $assessorExperience->position }}</p>
                            </div>
                        </div>
                    </div>
                    @if($assessorExperience->location)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">location_on</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $assessorExperience->location }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">category</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Experience Type</p>
                                @php
                                    $typeColors = [
                                        'assessment' => 'bg-blue-100 text-blue-700',
                                        'training' => 'bg-purple-100 text-purple-700',
                                        'industry' => 'bg-green-100 text-green-700',
                                        'other' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $typeColors[$assessorExperience->experience_type] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($assessorExperience->experience_type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Period Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Period</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">event</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Start Date</p>
                                <p class="font-semibold text-gray-900">{{ $assessorExperience->start_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">event</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">End Date</p>
                                @if($assessorExperience->is_current)
                                    <p class="font-semibold text-green-700">Present</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                        Current Position
                                    </span>
                                @elseif($assessorExperience->end_date)
                                    <p class="font-semibold text-gray-900">{{ $assessorExperience->end_date->format('d M Y') }}</p>
                                @else
                                    <p class="text-gray-400 text-sm">Not specified</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($assessorExperience->duration)
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">schedule</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Duration</p>
                                    <p class="font-semibold text-gray-900">{{ $assessorExperience->duration }} months</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($assessorExperience->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Job Description & Responsibilities</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $assessorExperience->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Reference Information -->
            @if($assessorExperience->reference_name || $assessorExperience->reference_contact)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Reference Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($assessorExperience->reference_name)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Reference Name</p>
                                        <p class="font-semibold text-gray-900">{{ $assessorExperience->reference_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($assessorExperience->reference_contact)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">contact_phone</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Reference Contact</p>
                                        <p class="font-semibold text-gray-900">{{ $assessorExperience->reference_contact }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Supporting Document -->
            @if($assessorExperience->document_file_path)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Supporting Document</h3>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">description</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ basename($assessorExperience->document_file_path) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Experience supporting document</p>
                        </div>
                        <a href="{{ asset('storage/' . $assessorExperience->document_file_path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">download</span>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Position Status</p>
                        @if($assessorExperience->is_current)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                Current
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                Past
                            </span>
                        @endif
                    </div>

                    @if($assessorExperience->duration)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Duration</p>
                            <p class="font-semibold text-gray-900">
                                @if($assessorExperience->duration >= 12)
                                    {{ floor($assessorExperience->duration / 12) }} year{{ floor($assessorExperience->duration / 12) > 1 ? 's' : '' }}
                                    @if($assessorExperience->duration % 12 > 0)
                                        {{ $assessorExperience->duration % 12 }} month{{ $assessorExperience->duration % 12 > 1 ? 's' : '' }}
                                    @endif
                                @else
                                    {{ $assessorExperience->duration }} month{{ $assessorExperience->duration > 1 ? 's' : '' }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold text-gray-900">{{ $assessorExperience->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="font-semibold text-gray-900">{{ $assessorExperience->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.assessor-experiences.edit', $assessorExperience) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.assessor-experiences.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.assessor-experiences.destroy', $assessorExperience) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this experience?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">delete</span>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
