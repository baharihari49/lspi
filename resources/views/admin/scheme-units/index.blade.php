@extends('layouts.admin')

@section('title', 'Competency Units')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Competency Units')
@section('page_description', $scheme->code . ' - Version ' . $version->version)

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

    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">Units</span>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">view_module</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $scheme->name }}</h2>
                        <p class="text-gray-600">Version {{ $version->version }} - {{ $version->units->count() }} Units</p>
                    </div>
                </div>
                <a href="{{ route('admin.schemes.versions.units.create', [$scheme, $version]) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Unit</span>
                </a>
            </div>
        </div>

        <!-- Units List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Competency Units</h3>
            </div>

            @if($version->units->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($version->units as $unit)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold
                                        @if($unit->unit_type === 'core') bg-blue-100 text-blue-700
                                        @elseif($unit->unit_type === 'optional') bg-amber-100 text-amber-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="font-mono text-sm text-gray-500">{{ $unit->code }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold
                                                @if($unit->unit_type === 'core') bg-blue-100 text-blue-700
                                                @elseif($unit->unit_type === 'optional') bg-amber-100 text-amber-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($unit->unit_type) }}
                                            </span>
                                            @if($unit->is_mandatory)
                                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                    Mandatory
                                                </span>
                                            @endif
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $unit->title }}</h4>
                                        @if($unit->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($unit->description, 150) }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            @if($unit->credit_hours)
                                                <span class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-base">schedule</span>
                                                    {{ $unit->credit_hours }} hours
                                                </span>
                                            @endif
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">category</span>
                                                {{ $unit->elements_count ?? 0 }} elements
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.schemes.versions.units.show', [$scheme, $version, $unit]) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.schemes.versions.units.edit', [$scheme, $version, $unit]) }}"
                                       class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.schemes.versions.units.destroy', [$scheme, $version, $unit]) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this unit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 block">view_module</span>
                    <p class="text-gray-500 font-medium">No competency units yet</p>
                    <p class="text-sm text-gray-400 mb-4">Add units to define the competencies for this scheme version</p>
                    <a href="{{ route('admin.schemes.versions.units.create', [$scheme, $version]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Add First Unit</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div>
            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Back to Version</span>
            </a>
        </div>
    </div>
@endsection
