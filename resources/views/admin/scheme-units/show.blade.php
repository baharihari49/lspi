@extends('layouts.admin')

@section('title', 'Unit Detail')

@php
    $active = 'schemes';
@endphp

@section('page_title', $unit->code)
@section('page_description', $unit->title)

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
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
            <a href="{{ route('admin.schemes.versions.units.index', [$scheme, $version]) }}" class="hover:text-blue-900">Units</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">{{ $unit->code }}</span>
        </nav>

        <!-- Unit Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center
                        @if($unit->unit_type === 'core') bg-blue-100 text-blue-600
                        @elseif($unit->unit_type === 'optional') bg-amber-100 text-amber-600
                        @else bg-gray-100 text-gray-600
                        @endif">
                        <span class="material-symbols-outlined text-3xl">view_module</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-mono text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $unit->code }}</span>
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($unit->unit_type === 'core') bg-blue-100 text-blue-700
                                @elseif($unit->unit_type === 'optional') bg-amber-100 text-amber-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($unit->unit_type) }}
                            </span>
                            @if($unit->is_mandatory)
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">
                                    Mandatory
                                </span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $unit->title }}</h2>
                        @if($unit->description)
                            <p class="text-gray-600">{{ $unit->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.schemes.versions.units.edit', [$scheme, $version, $unit]) }}"
                       class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.schemes.versions.units.destroy', [$scheme, $version, $unit]) }}"
                          method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this unit?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">delete</span>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Unit Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Elements</p>
                        <p class="text-xl font-bold text-gray-900">{{ $unit->elements->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">checklist</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Criteria</p>
                        <p class="text-xl font-bold text-gray-900">{{ $unit->elements->sum(fn($e) => $e->criteria->count()) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Credit Hours</p>
                        <p class="text-xl font-bold text-gray-900">{{ $unit->credit_hours ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">sort</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order</p>
                        <p class="text-xl font-bold text-gray-900">{{ $unit->order }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Elements Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Competency Elements</h3>
                    <p class="text-sm text-gray-500">Elements and criteria for this unit</p>
                </div>
                @if(Route::has('admin.schemes.versions.units.elements.create'))
                    <a href="{{ route('admin.schemes.versions.units.elements.create', [$scheme, $version, $unit]) }}"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Add Element</span>
                    </a>
                @endif
            </div>

            @if($unit->elements->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($unit->elements as $element)
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $element->code }}</span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $element->title }}</h4>
                                    @if($element->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ $element->description }}</p>
                                    @endif

                                    <!-- Criteria -->
                                    @if($element->criteria->count() > 0)
                                        <div class="mt-3 pl-4 border-l-2 border-gray-200">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kriteria Unjuk Kerja (KUK)</p>
                                            <ul class="space-y-1">
                                                @foreach($element->criteria as $criterion)
                                                    <li class="flex items-start gap-2 text-sm text-gray-700">
                                                        <span class="font-mono text-xs text-gray-400">{{ $criterion->code }}</span>
                                                        <span>{{ $criterion->description }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400 italic">No criteria defined</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(Route::has('admin.schemes.versions.units.elements.edit'))
                                        <a href="{{ route('admin.schemes.versions.units.elements.edit', [$scheme, $version, $unit, $element]) }}"
                                           class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 block">category</span>
                    <p class="text-gray-500 font-medium">No elements defined</p>
                    <p class="text-sm text-gray-400">Add elements to define the competencies for this unit</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="flex gap-3">
            <a href="{{ route('admin.schemes.versions.units.index', [$scheme, $version]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Back to Units</span>
            </a>
            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">layers</span>
                <span>View Version</span>
            </a>
        </div>
    </div>
@endsection
