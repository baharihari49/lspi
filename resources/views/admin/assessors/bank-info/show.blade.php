@extends('layouts.admin')

@section('title', 'Bank Information Details')

@php
    $active = 'assessor-bank-info';
@endphp

@section('page_title', 'Bank Information Details')
@section('page_description', 'View assessor banking and tax information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessor Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr($assessorBankInfo->assessor->full_name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ $assessorBankInfo->assessor->full_name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $assessorBankInfo->assessor->registration_number }}</p>
                        @if($assessorBankInfo->assessor->met_number)
                            <p class="text-sm text-gray-600">MET: {{ $assessorBankInfo->assessor->met_number }}</p>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('admin.assessors.show', $assessorBankInfo->assessor) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                                <span class="material-symbols-outlined text-sm">person</span>
                                <span>View Assessor Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Account Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bank Account Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">account_balance</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Bank Name</p>
                                <p class="font-semibold text-gray-900">{{ $assessorBankInfo->bank_name }}</p>
                            </div>
                        </div>
                    </div>
                    @if($assessorBankInfo->bank_code)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">tag</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Bank Code</p>
                                    <p class="font-semibold text-gray-900 font-mono">{{ $assessorBankInfo->bank_code }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($assessorBankInfo->branch_name)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">location_on</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Branch Name</p>
                                    <p class="font-semibold text-gray-900">{{ $assessorBankInfo->branch_name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">credit_card</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Account Number</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $assessorBankInfo->account_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Account Holder Name</p>
                                <p class="font-semibold text-gray-900">{{ $assessorBankInfo->account_holder_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Information (NPWP) -->
            @if($assessorBankInfo->npwp_number || $assessorBankInfo->tax_name || $assessorBankInfo->tax_address)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tax Information (NPWP)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($assessorBankInfo->npwp_number)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">badge</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">NPWP Number</p>
                                        <p class="font-semibold text-gray-900 font-mono">{{ $assessorBankInfo->npwp_number }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($assessorBankInfo->tax_name)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tax Name</p>
                                        <p class="font-semibold text-gray-900">{{ $assessorBankInfo->tax_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($assessorBankInfo->tax_address)
                            <div class="md:col-span-2">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">home</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tax Address</p>
                                        <p class="font-semibold text-gray-900">{{ $assessorBankInfo->tax_address }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Supporting Documents -->
            @if($assessorBankInfo->bankStatementFile || $assessorBankInfo->npwpFile)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Supporting Documents</h3>

                    <div class="space-y-3">
                        @if($assessorBankInfo->bankStatementFile)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">description</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $assessorBankInfo->bankStatementFile->filename }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Bank Statement - {{ number_format($assessorBankInfo->bankStatementFile->size / 1024, 2) }} KB</p>
                                </div>
                                <a href="{{ asset('storage/' . $assessorBankInfo->bankStatementFile->path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    <span>Download</span>
                                </a>
                            </div>
                        @endif

                        @if($assessorBankInfo->npwpFile)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">description</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $assessorBankInfo->npwpFile->filename }}</p>
                                    <p class="text-xs text-gray-500 mt-1">NPWP Document - {{ number_format($assessorBankInfo->npwpFile->size / 1024, 2) }} KB</p>
                                </div>
                                <a href="{{ asset('storage/' . $assessorBankInfo->npwpFile->path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    <span>Download</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Verification Information -->
            @if($assessorBankInfo->verification_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $assessorBankInfo->verification_notes }}</p>
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
                        <p class="text-xs text-gray-500 mb-1">Verification Status</p>
                        @php
                            $verificationColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'verified' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 {{ $verificationColors[$assessorBankInfo->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                            {{ ucfirst($assessorBankInfo->verification_status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Account Type</p>
                        @if($assessorBankInfo->is_primary)
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                Primary Account
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                Secondary Account
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Active Status</p>
                        @if($assessorBankInfo->is_active)
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

            <!-- Verification Details -->
            @if($assessorBankInfo->verified_at)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Verification Details</h3>
                    <div class="space-y-2 text-sm">
                        @if($assessorBankInfo->verifier)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Verified By:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorBankInfo->verifier->name }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Verified At:</span>
                            <span class="font-semibold text-gray-900">{{ $assessorBankInfo->verified_at->format('d M Y H:i') }}</span>
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
                        <span class="font-semibold text-gray-900">{{ $assessorBankInfo->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="font-semibold text-gray-900">{{ $assessorBankInfo->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.assessor-bank-info.edit', $assessorBankInfo) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.assessor-bank-info.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.assessor-bank-info.destroy', $assessorBankInfo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this bank information?')">
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
