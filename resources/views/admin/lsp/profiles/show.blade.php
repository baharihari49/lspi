@extends('layouts.admin')

@section('title', 'LSP Profile Details')

@php
    $active = 'lsp-profiles';
@endphp

@section('page_title', 'LSP Profile Details')
@section('page_description', 'View LSP organization profile information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">tag</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">LSP Code</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $lspProfile->code }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">corporate_fare</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">LSP Name</p>
                                <p class="font-semibold text-gray-900">{{ $lspProfile->name }}</p>
                            </div>
                        </div>
                    </div>
                    @if($lspProfile->legal_name)
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">gavel</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Legal Name</p>
                                    <p class="font-semibold text-gray-900">{{ $lspProfile->legal_name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($lspProfile->status)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">verified</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $lspProfile->status->label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- License Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">License Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">badge</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">License Number</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $lspProfile->license_number }}</p>
                            </div>
                        </div>
                    </div>
                    @if($lspProfile->license_issued_date)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">calendar_today</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">License Issued Date</p>
                                    <p class="font-semibold text-gray-900">{{ $lspProfile->license_issued_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($lspProfile->license_expiry_date)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">event</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">License Expiry Date</p>
                                    <p class="font-semibold text-gray-900">{{ $lspProfile->license_expiry_date->format('d M Y') }}</p>
                                    @if($lspProfile->license_expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            Expired
                                        </span>
                                    @elseif($lspProfile->license_expiry_date->lte(now()->addDays(30)))
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            Expiring Soon
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($lspProfile->accreditation_number)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">workspace_premium</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Accreditation Number</p>
                                    <p class="font-semibold text-gray-900 font-mono">{{ $lspProfile->accreditation_number }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($lspProfile->accreditation_expiry_date)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">event</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Accreditation Expiry</p>
                                    <p class="font-semibold text-gray-900">{{ $lspProfile->accreditation_expiry_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            @if($lspProfile->email || $lspProfile->phone || $lspProfile->fax || $lspProfile->website)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($lspProfile->email)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">email</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <a href="mailto:{{ $lspProfile->email }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $lspProfile->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->phone)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">phone</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                                        <a href="tel:{{ $lspProfile->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $lspProfile->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->fax)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">print</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Fax</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->fax }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->website)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">language</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Website</p>
                                        <a href="{{ $lspProfile->website }}" target="_blank" class="font-semibold text-blue-600 hover:text-blue-800">{{ $lspProfile->website }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Address Information -->
            @if($lspProfile->address || $lspProfile->city || $lspProfile->province || $lspProfile->postal_code)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Address Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($lspProfile->address)
                            <div class="md:col-span-2">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">home</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Address</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->address }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->city)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">location_city</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">City</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->city }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->province)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">map</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Province</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->province }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->postal_code)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">markunread_mailbox</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Postal Code</p>
                                        <p class="font-semibold text-gray-900 font-mono">{{ $lspProfile->postal_code }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Organization Information -->
            @if($lspProfile->description || $lspProfile->vision || $lspProfile->mission)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Organization Information</h3>

                    <div class="space-y-6">
                        @if($lspProfile->description)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">description</span>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-2">Description</p>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-gray-700 whitespace-pre-line">{{ $lspProfile->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->vision)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">visibility</span>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-2">Vision</p>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-gray-700 whitespace-pre-line">{{ $lspProfile->vision }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->mission)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">flag</span>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-2">Mission</p>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-gray-700 whitespace-pre-line">{{ $lspProfile->mission }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Director Information -->
            @if($lspProfile->director_name || $lspProfile->director_position || $lspProfile->director_phone || $lspProfile->director_email)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Director Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($lspProfile->director_name)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Director Name</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->director_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->director_position)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">work</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Position</p>
                                        <p class="font-semibold text-gray-900">{{ $lspProfile->director_position }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->director_phone)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">phone</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                                        <a href="tel:{{ $lspProfile->director_phone }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $lspProfile->director_phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($lspProfile->director_email)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">email</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <a href="mailto:{{ $lspProfile->director_email }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $lspProfile->director_email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                        <p class="text-xs text-gray-500 mb-1">Active Status</p>
                        @if($lspProfile->is_active)
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

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold text-gray-900">{{ $lspProfile->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="font-semibold text-gray-900">{{ $lspProfile->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.lsp-profiles.edit', $lspProfile) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.lsp-profiles.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.lsp-profiles.destroy', $lspProfile) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this LSP profile?')">
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
