@extends('layouts.admin')

@section('title', 'Assessee Details')

@php
    $active = 'assessees';
@endphp

@section('page_title', $assessee->full_name)
@section('page_description', 'Assessee profile and certification journey')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessee Information</h3>
                    <a href="{{ route('admin.assessees.edit', $assessee) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit Assessee</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        @if($assessee->photo)
                            <img src="{{ asset('storage/' . $assessee->photo) }}" alt="{{ $assessee->full_name }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                                {{ strtoupper(substr($assessee->full_name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessee->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $assessee->registration_number ?? 'No Registration Number' }}</p>
                            @if($assessee->id_number)
                                <p class="text-sm text-gray-600">{{ ucfirst($assessee->id_type) }}: {{ $assessee->id_number }}</p>
                            @endif
                            <div class="mt-2 flex flex-wrap gap-2">
                                @php
                                    $verificationColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'suspended' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $verificationColors[$assessee->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($assessee->verification_status) }}
                                </span>
                                @if($assessee->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($assessee->email)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">mail</span>
                                <div>
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->mobile)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                                <div>
                                    <p class="text-xs text-gray-600">Mobile</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->mobile }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->city)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">location_on</span>
                                <div>
                                    <p class="text-xs text-gray-600">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->city }}{{ $assessee->province ? ', ' . $assessee->province : '' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->date_of_birth)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">cake</span>
                                <div>
                                    <p class="text-xs text-gray-600">Birth</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $assessee->place_of_birth ? $assessee->place_of_birth . ', ' : '' }}{{ $assessee->date_of_birth->format('d M Y') }}
                                        @if($assessee->age)
                                            ({{ $assessee->age }} years)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->gender)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">person</span>
                                <div>
                                    <p class="text-xs text-gray-600">Gender</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($assessee->gender) }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->marital_status)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">favorite</span>
                                <div>
                                    <p class="text-xs text-gray-600">Marital Status</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($assessee->marital_status) }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->nationality)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">flag</span>
                                <div>
                                    <p class="text-xs text-gray-600">Nationality</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->nationality }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessee->current_company)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">business</span>
                                <div>
                                    <p class="text-xs text-gray-600">Company</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->current_company }}</p>
                                    @if($assessee->current_position)
                                        <p class="text-xs text-gray-500">{{ $assessee->current_position }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($assessee->bio)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Biography</p>
                            <p class="text-sm text-gray-700">{{ $assessee->bio }}</p>
                        </div>
                    @endif

                    @if($assessee->address)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Full Address</p>
                            <p class="text-sm text-gray-700">{{ $assessee->full_address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Documents ({{ $assessee->documents->count() }})</h3>
                    <a href="{{ route('admin.assessees.documents.create', $assessee) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Add Document</span>
                    </a>
                </div>

                @if($assessee->documents->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($assessee->documents as $document)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-600">description</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $document->document_name }}</h4>
                                        @if($document->documentType)
                                            <p class="text-sm text-gray-600 mt-1">{{ $document->documentType->name }}</p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @if($document->document_number)
                                                <span class="text-xs text-gray-500">{{ $document->document_number }}</span>
                                            @endif
                                            @php
                                                $verificationColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'verified' => 'bg-green-100 text-green-700',
                                                    'rejected' => 'bg-red-100 text-red-700',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 {{ $verificationColors[$document->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded text-xs font-semibold">
                                                {{ ucfirst($document->verification_status) }}
                                            </span>
                                        </div>
                                        @if($document->expiry_date)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Expires: {{ $document->expiry_date->format('d M Y') }}
                                                @if($document->is_expired)
                                                    <span class="text-red-600 font-semibold">(Expired)</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.assessees.documents.download', [$assessee, $document]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Download">
                                        <span class="material-symbols-outlined text-lg">download</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No documents uploaded yet.</p>
                @endif
            </div>

            <!-- Employment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Employment History ({{ $assessee->employmentInfo->count() }})</h3>
                    <a href="{{ route('admin.assessees.employment.create', $assessee) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Add Employment</span>
                    </a>
                </div>

                @if($assessee->employmentInfo->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessee->employmentInfo->sortByDesc('is_current')->sortByDesc('start_date') as $employment)
                            <div class="border-l-4 {{ $employment->is_current ? 'border-green-500' : 'border-gray-300' }} pl-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $employment->position_title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $employment->company_name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $employment->start_date->format('M Y') }} -
                                            @if($employment->is_current)
                                                <span class="text-green-600 font-semibold">Present</span>
                                            @elseif($employment->end_date)
                                                {{ $employment->end_date->format('M Y') }}
                                            @else
                                                -
                                            @endif
                                            @if($employment->duration)
                                                ({{ $employment->duration }})
                                            @endif
                                        </p>
                                    </div>
                                    @if($employment->is_current)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Current</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No employment history added yet.</p>
                @endif
            </div>

            <!-- Education History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Education History ({{ $assessee->educationHistory->count() }})</h3>
                    <a href="{{ route('admin.assessees.education.create', $assessee) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Add Education</span>
                    </a>
                </div>

                @if($assessee->educationHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessee->educationHistory->sortByDesc('start_date') as $education)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-indigo-600">school</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $education->institution_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $education->degree_name ?? $education->education_level_label }}</p>
                                        @if($education->major)
                                            <p class="text-sm text-gray-600">{{ $education->major }}</p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $education->start_date->format('Y') }} -
                                                @if($education->is_current)
                                                    Present
                                                @elseif($education->end_date)
                                                    {{ $education->end_date->format('Y') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                            @if($education->gpa)
                                                <span class="text-xs text-gray-500">• GPA: {{ $education->gpa }}{{ $education->gpa_scale ? '/' . $education->gpa_scale : '' }}</span>
                                            @endif
                                            @if($education->honors)
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">{{ $education->honors }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No education history added yet.</p>
                @endif
            </div>

            <!-- Experience -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Experience Portfolio ({{ $assessee->experiences->count() }})</h3>
                    <a href="{{ route('admin.assessees.experience.create', $assessee) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Add Experience</span>
                    </a>
                </div>

                @if($assessee->experiences->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessee->experiences->sortByDesc('is_ongoing')->sortByDesc('start_date') as $experience)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    @php
                                        $iconMap = [
                                            'professional' => 'work',
                                            'project' => 'assignment',
                                            'volunteer' => 'volunteer_activism',
                                            'certification' => 'workspace_premium',
                                            'training' => 'model_training',
                                            'publication' => 'article',
                                            'award' => 'emoji_events',
                                            'other' => 'star',
                                        ];
                                    @endphp
                                    <span class="material-symbols-outlined text-purple-600">{{ $iconMap[$experience->experience_type] ?? 'star' }}</span>
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $experience->title }}</h4>
                                                @if($experience->organization)
                                                    <p class="text-sm text-gray-600">{{ $experience->organization }}</p>
                                                @endif
                                            </div>
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-semibold whitespace-nowrap">
                                                {{ $experience->experience_type_label }}
                                            </span>
                                        </div>
                                        @if($experience->start_date)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $experience->start_date->format('M Y') }}
                                                @if($experience->is_ongoing)
                                                    - <span class="text-green-600 font-semibold">Ongoing</span>
                                                @elseif($experience->end_date)
                                                    - {{ $experience->end_date->format('M Y') }}
                                                @endif
                                            </p>
                                        @endif
                                        @if($experience->relevance_score)
                                            <div class="mt-2">
                                                <span class="text-xs text-gray-600">Relevance: {{ $experience->relevance_score }}/10</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No experience added yet.</p>
                @endif
            </div>

            <!-- Emergency Contact -->
            @if($assessee->emergency_contact_name)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Emergency Contact</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Name</p>
                                <p class="font-semibold text-gray-900">{{ $assessee->emergency_contact_name }}</p>
                            </div>
                        </div>
                        @if($assessee->emergency_contact_relation)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">family_restroom</span>
                                <div>
                                    <p class="text-xs text-gray-600">Relation</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->emergency_contact_relation }}</p>
                                </div>
                            </div>
                        @endif
                        @if($assessee->emergency_contact_phone)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                                <div>
                                    <p class="text-xs text-gray-600">Phone</p>
                                    <p class="font-semibold text-gray-900">{{ $assessee->emergency_contact_phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.assessees.edit', $assessee) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Assessee
                    </a>

                    <a href="{{ route('admin.assessees.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    <form action="{{ route('admin.assessees.destroy', $assessee) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessee?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                            Delete Assessee
                        </button>
                    </form>
                </div>

                @if($assessee->verified_at)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            <span class="font-semibold">Verified:</span><br>
                            {{ $assessee->verified_at->format('d M Y H:i') }}<br>
                            by {{ $assessee->verifiedBy?->name ?? 'Unknown' }}
                        </p>
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $assessee->created_at->format('d M Y H:i') }}
                    </p>
                    @if($assessee->updated_at != $assessee->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $assessee->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Documents</span>
                        <span class="font-bold text-gray-900">{{ $assessee->documents->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Employment Records</span>
                        <span class="font-bold text-gray-900">{{ $assessee->employmentInfo->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Education Records</span>
                        <span class="font-bold text-gray-900">{{ $assessee->educationHistory->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Experience Records</span>
                        <span class="font-bold text-gray-900">{{ $assessee->experiences->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
