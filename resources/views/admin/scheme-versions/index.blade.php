@extends('layouts.admin')

@section('title', 'Scheme Versions - ' . $scheme->name)

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Scheme Versions')
@section('page_description', $scheme->code . ' - ' . $scheme->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">Versions</span>
        </nav>

        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $scheme->name }}</h2>
                <p class="text-gray-600 mt-1">Manage certification scheme versions</p>
            </div>
            <a href="{{ route('admin.schemes.versions.create', $scheme) }}" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">add</span>
                <span>Add Version</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Versions List -->
        @if($scheme->versions->count() > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach($scheme->versions as $version)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-900">Version {{ $version->version }}</h3>

                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-700',
                                            'active' => 'bg-green-100 text-green-700',
                                            'archived' => 'bg-blue-100 text-blue-700',
                                            'deprecated' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusColors[$version->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold uppercase">
                                        {{ $version->status }}
                                    </span>

                                    @if($version->is_current)
                                        <span class="px-3 py-1 bg-blue-900 text-white rounded-full text-xs font-semibold uppercase">
                                            CURRENT
                                        </span>
                                    @endif
                                </div>

                                @if($version->changes_summary)
                                    <p class="text-gray-700 mb-4">{{ $version->changes_summary }}</p>
                                @endif

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 mb-1">Units</p>
                                        <p class="font-semibold text-gray-900">{{ $version->units_count }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Requirements</p>
                                        <p class="font-semibold text-gray-900">{{ $version->requirements_count }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Documents</p>
                                        <p class="font-semibold text-gray-900">{{ $version->documents_count }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Effective Date</p>
                                        <p class="font-semibold text-gray-900">{{ $version->effective_date?->format('d M Y') ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($version->approved_at)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-sm text-gray-600">
                                            Approved by <span class="font-semibold">{{ $version->approver?->name ?? 'Unknown' }}</span>
                                            on {{ $version->approved_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-2 ml-4">
                                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-semibold transition text-sm">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                    <span>View</span>
                                </a>

                                <a href="{{ route('admin.schemes.versions.edit', [$scheme, $version]) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-900 rounded-lg font-semibold transition text-sm">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                    <span>Edit</span>
                                </a>

                                @if(!$version->is_current && $version->status === 'active')
                                    <form action="{{ route('admin.schemes.versions.set-current', [$scheme, $version]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-green-100 hover:bg-green-200 text-green-900 rounded-lg font-semibold transition text-sm">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                            <span>Set Current</span>
                                        </button>
                                    </form>
                                @endif

                                @if($version->status === 'draft' && !$version->approved_at)
                                    <form action="{{ route('admin.schemes.versions.approve', [$scheme, $version]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-green-100 hover:bg-green-200 text-green-900 rounded-lg font-semibold transition text-sm">
                                            <span class="material-symbols-outlined text-lg">verified</span>
                                            <span>Approve</span>
                                        </button>
                                    </form>
                                @endif

                                @if(!$version->is_current)
                                    <form action="{{ route('admin.schemes.versions.destroy', [$scheme, $version]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this version?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-900 rounded-lg font-semibold transition text-sm">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">history</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No versions created yet</h3>
                <p class="text-gray-600 mb-6">Create a version to add units and requirements</p>
                <a href="{{ route('admin.schemes.versions.create', $scheme) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Create First Version</span>
                </a>
            </div>
        @endif
    </div>
@endsection
