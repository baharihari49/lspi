@extends('layouts.admin')

@section('title', 'Competency Scope Details')

@php
    $active = 'assessor-competency-scopes';
@endphp

@section('page_title', 'Competency Scope Details')
@section('page_description', 'View assessor competency certification information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessor Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr($assessorCompetencyScope->assessor->full_name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ $assessorCompetencyScope->assessor->full_name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $assessorCompetencyScope->assessor->registration_number }}</p>
                        @if($assessorCompetencyScope->assessor->met_number)
                            <p class="text-sm text-gray-600">MET: {{ $assessorCompetencyScope->assessor->met_number }}</p>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('admin.assessors.show', $assessorCompetencyScope->assessor) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                                <span class="material-symbols-outlined text-sm">person</span>
                                <span>View Assessor Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scheme Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Scheme</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">bookmark</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Scheme Code</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $assessorCompetencyScope->scheme_code }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">description</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Scheme Name</p>
                                <p class="font-semibold text-gray-900">{{ $assessorCompetencyScope->scheme_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competency Unit -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Unit</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">tag</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Unit Code</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $assessorCompetencyScope->competency_unit_code }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">title</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Unit Title</p>
                                <p class="font-semibold text-gray-900">{{ $assessorCompetencyScope->competency_unit_title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">badge</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Certificate Number</p>
                                @if($assessorCompetencyScope->certificate_number)
                                    <p class="font-semibold text-gray-900 font-mono">{{ $assessorCompetencyScope->certificate_number }}</p>
                                @else
                                    <p class="text-gray-400 text-sm">Not provided</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">calendar_today</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Issued Date</p>
                                @if($assessorCompetencyScope->certificate_issued_date)
                                    <p class="font-semibold text-gray-900">{{ $assessorCompetencyScope->certificate_issued_date->format('d M Y') }}</p>
                                @else
                                    <p class="text-gray-400 text-sm">Not provided</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">event</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Expiry Date</p>
                                @if($assessorCompetencyScope->certificate_expiry_date)
                                    <p class="font-semibold text-gray-900">{{ $assessorCompetencyScope->certificate_expiry_date->format('d M Y') }}</p>
                                    @if($assessorCompetencyScope->certificate_expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            Expired
                                        </span>
                                    @elseif($assessorCompetencyScope->certificate_expiry_date->lte(now()->addDays(30)))
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            Expiring Soon
                                        </span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            Valid
                                        </span>
                                    @endif
                                @else
                                    <p class="text-gray-400 text-sm">No expiry</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            @if($assessorCompetencyScope->approval_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $assessorCompetencyScope->approval_notes }}</p>
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
                        <p class="text-xs text-gray-500 mb-1">Approval Status</p>
                        @php
                            $approvalColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'expired' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 {{ $approvalColors[$assessorCompetencyScope->approval_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                            {{ ucfirst($assessorCompetencyScope->approval_status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Active Status</p>
                        @if($assessorCompetencyScope->is_active)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                Active
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Approval Details -->
            @if($assessorCompetencyScope->approved_at)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Approval Details</h3>
                    <div class="space-y-2 text-sm">
                        @if($assessorCompetencyScope->approver)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Approved By:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->approver->name }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Approved At:</span>
                            <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->approved_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.assessor-competency-scopes.edit', $assessorCompetencyScope) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.assessor-competency-scopes.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.assessor-competency-scopes.destroy', $assessorCompetencyScope) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this competency scope?')">
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
