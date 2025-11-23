@extends('layouts.admin')

@section('title', 'Evidence Details')

@php
    $active = 'apl02-evidence';
@endphp

@section('page_title', 'Evidence Details')
@section('page_description', 'View APL-02 portfolio evidence information')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Evidence Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Evidence Information</h3>
                        <p class="text-sm text-gray-600">{{ $evidence->evidence_number }}</p>
                    </div>
                    @php
                        $typeColors = [
                            'document' => 'bg-blue-100 text-blue-800',
                            'certificate' => 'bg-purple-100 text-purple-800',
                            'work_sample' => 'bg-green-100 text-green-800',
                            'project' => 'bg-orange-100 text-orange-800',
                            'photo' => 'bg-pink-100 text-pink-800',
                            'video' => 'bg-red-100 text-red-800',
                            'presentation' => 'bg-indigo-100 text-indigo-800',
                            'log_book' => 'bg-yellow-100 text-yellow-800',
                            'portfolio' => 'bg-teal-100 text-teal-800',
                            'other' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded text-sm font-semibold {{ $typeColors[$evidence->evidence_type] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $evidence->evidence_type_label }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                        <p class="text-gray-900">{{ $evidence->title }}</p>
                    </div>

                    @if($evidence->description)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $evidence->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                            <p class="text-gray-900">{{ $evidence->assessee->full_name }}</p>
                            <p class="text-xs text-gray-600">{{ $evidence->assessee->registration_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit</label>
                            <p class="text-gray-900">{{ $evidence->apl02Unit->unit_code }}</p>
                            <p class="text-xs text-gray-600">{{ Str::limit($evidence->apl02Unit->unit_title, 40) }}</p>
                        </div>
                    </div>

                    @if($evidence->issued_by || $evidence->issuer_organization)
                        <div class="grid grid-cols-2 gap-4">
                            @if($evidence->issued_by)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Issued By</label>
                                    <p class="text-gray-900">{{ $evidence->issued_by }}</p>
                                </div>
                            @endif

                            @if($evidence->issuer_organization)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Issuer Organization</label>
                                    <p class="text-gray-900">{{ $evidence->issuer_organization }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($evidence->certificate_number)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Certificate Number</label>
                            <p class="text-gray-900">{{ $evidence->certificate_number }}</p>
                        </div>
                    @endif

                    @if($evidence->validity_start_date || $evidence->validity_end_date)
                        <div class="grid grid-cols-2 gap-4">
                            @if($evidence->validity_start_date)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Validity Start Date</label>
                                    <p class="text-gray-900">{{ $evidence->validity_start_date->format('d M Y') }}</p>
                                </div>
                            @endif

                            @if($evidence->validity_end_date)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Validity End Date</label>
                                    <p class="text-gray-900">{{ $evidence->validity_end_date->format('d M Y') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- File Information -->
            @if($evidence->file_path || $evidence->external_url)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">File Information</h3>

                    <div class="space-y-4">
                        @if($evidence->file_path)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">File Name</label>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600">description</span>
                                    <p class="text-gray-900">{{ $evidence->file_name }}</p>
                                </div>
                            </div>

                            @if($evidence->original_filename)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Original Filename</label>
                                    <p class="text-gray-900">{{ $evidence->original_filename }}</p>
                                </div>
                            @endif
                        @endif

                        @if($evidence->external_url)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">External URL</label>
                                <a href="{{ $evidence->external_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    {{ $evidence->external_url }}
                                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Element Mappings -->
            @if($evidence->evidenceMaps->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mapped to Competency Elements</h3>

                    <div class="space-y-3">
                        @foreach($evidence->evidenceMaps as $map)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $map->schemeElement->code }}</p>
                                        <p class="text-sm text-gray-600">{{ $map->schemeElement->title }}</p>
                                    </div>
                                    @php
                                        $coverageColors = [
                                            'full' => 'bg-green-100 text-green-800',
                                            'partial' => 'bg-yellow-100 text-yellow-800',
                                            'supplementary' => 'bg-blue-100 text-blue-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $coverageColors[$map->coverage_level] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($map->coverage_level) }}
                                    </span>
                                </div>

                                @if($map->assessor_evaluation !== 'pending')
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        @php
                                            $evaluationColors = [
                                                'accepted' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $evaluationColors[$map->assessor_evaluation] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $map->assessor_evaluation)) }}
                                        </span>
                                    </div>
                                @endif

                                @if($map->evaluation_notes)
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="italic">{{ $map->evaluation_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Assessment Notes -->
            @if($evidence->assessor_notes || $evidence->verification_notes || $evidence->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>

                    <div class="space-y-4">
                        @if($evidence->assessor_notes)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Assessor Notes</label>
                                <p class="text-gray-900">{{ $evidence->assessor_notes }}</p>
                            </div>
                        @endif

                        @if($evidence->verification_notes)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Verification Notes</label>
                                <p class="text-gray-900">{{ $evidence->verification_notes }}</p>
                            </div>
                        @endif

                        @if($evidence->notes)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Additional Notes</label>
                                <p class="text-gray-900">{{ $evidence->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-2">
                    @if($evidence->file_path)
                        <a href="{{ route('admin.apl02.evidence.download', $evidence) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">download</span>
                            <span>Download File</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.apl02.evidence.edit', $evidence) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit Evidence</span>
                    </a>

                    <a href="{{ route('admin.apl02.units.show', $evidence->apl02Unit) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">folder_open</span>
                        <span>View Unit</span>
                    </a>

                    <a href="{{ route('admin.apl02.evidence.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>

                    <form action="{{ route('admin.apl02.evidence.destroy', $evidence) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this evidence?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">delete</span>
                            <span>Delete Evidence</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Verification</label>
                        @php
                            $verificationColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'verified' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'requires_clarification' => 'bg-orange-100 text-orange-800',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $verificationColors[$evidence->verification_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $evidence->verification_status_label }}
                        </span>
                    </div>

                    @if($evidence->verified_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Verified At</label>
                            <p class="text-sm text-gray-900">{{ $evidence->verified_at->format('d M Y H:i') }}</p>
                            @if($evidence->verifiedBy)
                                <p class="text-xs text-gray-600">by {{ $evidence->verifiedBy->name }}</p>
                            @endif
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment</label>
                        @php
                            $assessmentColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'valid' => 'bg-green-100 text-green-800',
                                'invalid' => 'bg-red-100 text-red-800',
                                'insufficient' => 'bg-orange-100 text-orange-800',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $assessmentColors[$evidence->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $evidence->assessment_result_label }}
                        </span>
                    </div>

                    @if($evidence->submitted_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Submitted At</label>
                            <p class="text-sm text-gray-900">{{ $evidence->submitted_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Evidence Flags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Quality</h3>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Authentic</span>
                        @if($evidence->is_authentic)
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        @else
                            <span class="material-symbols-outlined text-gray-400">cancel</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Current</span>
                        @if($evidence->is_current)
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        @else
                            <span class="material-symbols-outlined text-gray-400">cancel</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Sufficient</span>
                        @if($evidence->is_sufficient)
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        @else
                            <span class="material-symbols-outlined text-gray-400">cancel</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Metadata</h3>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Views</span>
                        <span class="font-semibold text-gray-900">{{ $evidence->view_count }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Created</span>
                        <span class="font-semibold text-gray-900">{{ $evidence->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated</span>
                        <span class="font-semibold text-gray-900">{{ $evidence->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
