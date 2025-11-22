@extends('layouts.admin')

@section('title', 'Scheme Details')

@php
    $active = 'schemes';
@endphp

@section('page_title', $scheme->name)
@section('page_description', $scheme->code)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Basic Information</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.schemes.edit', $scheme) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Scheme Code</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $scheme->code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Scheme Name</p>
                        <p class="font-semibold text-gray-900">{{ $scheme->name }}</p>
                    </div>
                    @if($scheme->occupation_title)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Occupation Title</p>
                        <p class="text-sm text-gray-900">{{ $scheme->occupation_title }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Scheme Type</p>
                        @php
                            $typeColors = [
                                'occupation' => 'bg-blue-100 text-blue-700',
                                'cluster' => 'bg-green-100 text-green-700',
                                'qualification' => 'bg-purple-100 text-purple-700',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 {{ $typeColors[$scheme->scheme_type] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                            {{ ucfirst($scheme->scheme_type) }}
                        </span>
                    </div>
                    @if($scheme->qualification_level)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">KKNI Level</p>
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                            Level {{ $scheme->qualification_level }}
                        </span>
                    </div>
                    @endif
                    @if($scheme->sector)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Sector</p>
                        <p class="text-sm text-gray-900">{{ $scheme->sector }}</p>
                    </div>
                    @endif
                </div>

                @if($scheme->description)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500 mb-2">Description</p>
                    <p class="text-sm text-gray-700">{{ $scheme->description }}</p>
                </div>
                @endif
            </div>

            <!-- Versions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Versions</h3>
                    <a href="{{ route('admin.schemes.versions.index', $scheme) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-lg">settings</span>
                        <span>Manage Versions</span>
                    </a>
                </div>

                @if($scheme->versions->count() > 0)
                    <div class="space-y-4">
                        @foreach($scheme->versions as $version)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $version->is_current ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold text-gray-900">Version {{ $version->version }}</span>
                                            @if($version->is_current)
                                                <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">Current</span>
                                            @endif
                                        </div>
                                        @if($version->changes_summary)
                                            <p class="text-sm text-gray-600 mt-1">{{ $version->changes_summary }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-100 text-gray-700',
                                                'active' => 'bg-green-100 text-green-700',
                                                'archived' => 'bg-yellow-100 text-yellow-700',
                                                'deprecated' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 {{ $statusColors[$version->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                            {{ ucfirst($version->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                                    @if($version->effective_date)
                                    <div>
                                        <p class="text-gray-500">Effective</p>
                                        <p class="font-semibold text-gray-900">{{ $version->effective_date->format('d M Y') }}</p>
                                    </div>
                                    @endif
                                    @if($version->expiry_date)
                                    <div>
                                        <p class="text-gray-500">Expiry</p>
                                        <p class="font-semibold text-gray-900">{{ $version->expiry_date->format('d M Y') }}</p>
                                    </div>
                                    @endif
                                    <div>
                                        <p class="text-gray-500">Units</p>
                                        <p class="font-semibold text-gray-900">{{ $version->units->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Requirements</p>
                                        <p class="font-semibold text-gray-900">{{ $version->requirements->count() }}</p>
                                    </div>
                                </div>

                                <!-- Units (collapsible) -->
                                @if($version->units->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="font-semibold text-gray-900 mb-3 text-sm">Competency Units ({{ $version->units->count() }})</h4>
                                        <div class="space-y-3">
                                            @foreach($version->units as $unit)
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <span class="font-mono text-xs text-gray-600">{{ $unit->code }}</span>
                                                                @php
                                                                    $unitTypeColors = [
                                                                        'core' => 'bg-blue-100 text-blue-700',
                                                                        'optional' => 'bg-purple-100 text-purple-700',
                                                                        'supporting' => 'bg-green-100 text-green-700',
                                                                    ];
                                                                @endphp
                                                                <span class="px-2 py-0.5 {{ $unitTypeColors[$unit->unit_type] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs font-semibold">
                                                                    {{ ucfirst($unit->unit_type) }}
                                                                </span>
                                                                @if($unit->is_mandatory)
                                                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">Mandatory</span>
                                                                @endif
                                                            </div>
                                                            <p class="font-semibold text-sm text-gray-900">{{ $unit->title }}</p>
                                                            @if($unit->credit_hours)
                                                                <p class="text-xs text-gray-500 mt-1">{{ $unit->credit_hours }} credit hours</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if($unit->elements->count() > 0)
                                                        <div class="mt-3 ml-4 space-y-2">
                                                            @foreach($unit->elements as $element)
                                                                <div class="border-l-2 border-blue-300 pl-3">
                                                                    <div class="flex items-center gap-2">
                                                                        @if($element->code)
                                                                            <span class="font-mono text-xs text-gray-500">{{ $element->code }}</span>
                                                                        @endif
                                                                        <p class="text-sm font-medium text-gray-800">{{ $element->title }}</p>
                                                                    </div>

                                                                    @if($element->criteria->count() > 0)
                                                                        <ul class="mt-2 ml-4 space-y-1">
                                                                            @foreach($element->criteria as $criterion)
                                                                                <li class="text-xs text-gray-600 flex items-start gap-2">
                                                                                    @if($criterion->code)
                                                                                        <span class="font-mono text-gray-400 flex-shrink-0">{{ $criterion->code }}.</span>
                                                                                    @endif
                                                                                    <span>{{ $criterion->description }}</span>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Requirements -->
                                @if($version->requirements->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="font-semibold text-gray-900 mb-3 text-sm">Requirements ({{ $version->requirements->count() }})</h4>
                                        <div class="space-y-2">
                                            @foreach($version->requirements as $requirement)
                                                <div class="flex items-start gap-3">
                                                    <span class="material-symbols-outlined text-sm text-gray-400 mt-0.5">
                                                        {{ $requirement->is_mandatory ? 'check_circle' : 'radio_button_unchecked' }}
                                                    </span>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <p class="text-sm font-medium text-gray-900">{{ $requirement->title }}</p>
                                                            @php
                                                                $reqTypeColors = [
                                                                    'education' => 'bg-blue-100 text-blue-700',
                                                                    'experience' => 'bg-green-100 text-green-700',
                                                                    'certification' => 'bg-purple-100 text-purple-700',
                                                                    'training' => 'bg-yellow-100 text-yellow-700',
                                                                    'physical' => 'bg-red-100 text-red-700',
                                                                    'other' => 'bg-gray-100 text-gray-700',
                                                                ];
                                                            @endphp
                                                            <span class="px-2 py-0.5 {{ $reqTypeColors[$requirement->requirement_type] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs font-semibold">
                                                                {{ ucfirst($requirement->requirement_type) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-1">{{ $requirement->description }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">inventory_2</span>
                        <p class="text-gray-500 font-semibold">No versions created yet</p>
                        <p class="text-sm text-gray-400 mt-1 mb-4">Create a version to add units and requirements</p>
                        <a href="{{ route('admin.schemes.versions.create', $scheme) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">add</span>
                            <span>Create First Version</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Info & Actions -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-0 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.schemes.edit', $scheme) }}" class="w-full flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Scheme</span>
                        </a>
                        <a href="{{ route('admin.schemes.index') }}" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            <span>Back to List</span>
                        </a>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Status Information</h3>
                    <div class="space-y-3">
                        @if($scheme->status)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Current Status</p>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                {{ $scheme->status->label }}
                            </span>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Active Status</p>
                            @if($scheme->is_active)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Inactive</span>
                            @endif
                        </div>
                        @if($scheme->effective_date)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Effective Date</p>
                            <p class="text-sm text-gray-900">{{ $scheme->effective_date->format('d M Y') }}</p>
                        </div>
                        @endif
                        @if($scheme->review_date)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Review Date</p>
                            <p class="text-sm text-gray-900">{{ $scheme->review_date->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                    <div class="space-y-2 text-sm">
                        @if($scheme->creator)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Created By</p>
                            <p class="font-medium text-gray-900">{{ $scheme->creator->name }}</p>
                            <p class="text-xs text-gray-500">{{ $scheme->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                            <p class="text-xs text-gray-900">{{ $scheme->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Versions</p>
                            <p class="font-semibold text-gray-900">{{ $scheme->versions->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
