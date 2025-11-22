@extends('layouts.admin')

@section('title', 'Assessor Details')

@php
    $active = 'assessors';
@endphp

@section('page_title', $assessor->full_name)
@section('page_description', 'Assessor profile and certification details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessor Information</h3>
                    <a href="{{ route('admin.assessors.edit', $assessor) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit Assessor</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($assessor->full_name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessor->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $assessor->registration_number }}</p>
                            @if($assessor->met_number)
                                <p class="text-sm text-gray-600">MET: {{ $assessor->met_number }}</p>
                            @endif
                            <div class="mt-2 flex flex-wrap gap-2">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-700',
                                        'inactive' => 'bg-gray-100 text-gray-600',
                                        'suspended' => 'bg-red-100 text-red-700',
                                        'expired' => 'bg-red-100 text-red-700',
                                    ];
                                    $verificationColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusColors[$assessor->registration_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($assessor->registration_status) }}
                                </span>
                                <span class="px-3 py-1 {{ $verificationColors[$assessor->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($assessor->verification_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">mail</span>
                            <div>
                                <p class="text-xs text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">phone</span>
                            <div>
                                <p class="text-xs text-gray-600">Mobile</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->mobile }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">location_on</span>
                            <div>
                                <p class="text-xs text-gray-600">Location</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->city }}, {{ $assessor->province }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">cake</span>
                            <div>
                                <p class="text-xs text-gray-600">Birth</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->birth_place }}, {{ $assessor->birth_date->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">school</span>
                            <div>
                                <p class="text-xs text-gray-600">Education</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->education_level }}{{ $assessor->major ? ' - ' . $assessor->major : '' }}</p>
                            </div>
                        </div>

                        @if($assessor->company)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">business</span>
                                <div>
                                    <p class="text-xs text-gray-600">Company</p>
                                    <p class="font-semibold text-gray-900">{{ $assessor->company }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Competency Scopes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Scopes</h3>

                @if($assessor->competencyScopes->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($assessor->competencyScopes as $scope)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-indigo-600">workspace_premium</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $scope->scheme_name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $scope->competency_unit_title }}</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ $scope->scheme_code }}</span>
                                            @if($scope->certificate_number)
                                                <span class="text-xs text-gray-500">• {{ $scope->certificate_number }}</span>
                                            @endif
                                            @php
                                                $approvalColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'approved' => 'bg-green-100 text-green-700',
                                                    'rejected' => 'bg-red-100 text-red-700',
                                                    'expired' => 'bg-red-100 text-red-700',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 {{ $approvalColors[$scope->approval_status] ?? 'bg-gray-100 text-gray-600' }} rounded text-xs font-semibold">
                                                {{ ucfirst($scope->approval_status) }}
                                            </span>
                                        </div>
                                        @if($scope->certificate_expiry_date)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Expires: {{ $scope->certificate_expiry_date->format('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">workspace_premium</span>
                        <p class="text-gray-500 mt-2">No competency scopes assigned</p>
                    </div>
                @endif
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Documents</h3>

                @if($assessor->documents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($assessor->documents as $document)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-600">description</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $document->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $document->documentType->name ?? '-' }}</p>
                                        @php
                                            $docColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'approved' => 'bg-green-100 text-green-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="inline-block mt-2 px-2 py-0.5 {{ $docColors[$document->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded text-xs font-semibold">
                                            {{ ucfirst($document->verification_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">description</span>
                        <p class="text-gray-500 mt-2">No documents uploaded</p>
                    </div>
                @endif
            </div>

            <!-- Experiences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Work Experiences</h3>

                @if($assessor->experiences->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessor->experiences as $experience)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-green-600">work_history</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $experience->position }}</h4>
                                        <p class="text-sm text-gray-600">{{ $experience->organization_name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $experience->start_date->format('M Y') }} -
                                            {{ $experience->is_current ? 'Present' : $experience->end_date->format('M Y') }}
                                            ({{ $experience->duration }} months)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">work_history</span>
                        <p class="text-gray-500 mt-2">No experiences recorded</p>
                    </div>
                @endif
            </div>

            <!-- Bank Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bank Information</h3>

                @if($assessor->bankInfo->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($assessor->bankInfo as $bank)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-emerald-600">account_balance</span>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-semibold text-gray-900">{{ $bank->bank_name }}</h4>
                                            @if($bank->is_primary)
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Primary</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $bank->account_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $bank->account_holder_name }}</p>
                                        @if($bank->npwp_number)
                                            <p class="text-xs text-gray-500 mt-1">NPWP: {{ $bank->npwp_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">account_balance</span>
                        <p class="text-gray-500 mt-2">No bank information recorded</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Metadata -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Registration Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Registration Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Registration Date</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->registration_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Valid Until</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->valid_until->format('d M Y') }}</p>
                        @if($assessor->valid_until->isPast())
                            <p class="text-xs text-red-600 mt-1">Expired {{ $assessor->valid_until->diffForHumans() }}</p>
                        @elseif($assessor->valid_until->lte(now()->addDays(30)))
                            <p class="text-xs text-yellow-600 mt-1">Expiring soon</p>
                        @endif
                    </div>
                    @if($assessor->verification_status === 'verified' && $assessor->verified_at)
                        <div>
                            <p class="text-gray-600">Verified At</p>
                            <p class="font-semibold text-gray-900">{{ $assessor->verified_at->format('d M Y H:i') }}</p>
                        </div>
                        @if($assessor->verifier)
                            <div>
                                <p class="text-gray-600">Verified By</p>
                                <p class="font-semibold text-gray-900">{{ $assessor->verifier->name }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- User Account -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">User Account</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Name</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Account Metadata</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Created</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Last Updated</p>
                        <p class="font-semibold text-gray-900">{{ $assessor->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.assessors.edit', $assessor) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit Assessor</span>
                    </a>
                    <a href="{{ route('admin.assessors.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.assessors.destroy', $assessor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">delete</span>
                            <span>Delete Assessor</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
