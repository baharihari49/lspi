@extends('layouts.admin')

@section('title', 'Elements - ' . $unit->title)

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Competency Elements')
@section('page_description', $unit->code . ' - ' . $unit->title)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">{{ $unit->code }}</span>
        </nav>

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $unit->title }}</h2>
                <p class="text-gray-600 mt-1">{{ $unit->code }}</p>
            </div>
            <a href="{{ route('admin.schemes.versions.units.elements.create', [$scheme, $version, $unit]) }}" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">add</span>
                <span>Add Element</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($unit->elements->count() > 0)
            <div class="space-y-4">
                @foreach($unit->elements as $element)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-mono text-sm text-gray-600">{{ $element->code }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $element->title }}</h3>
                                @if($element->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $element->description }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">{{ $element->criteria_count }} Criteria (KUK)</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Manage Criteria">
                                    <span class="material-symbols-outlined">list</span>
                                </a>
                                <a href="{{ route('admin.schemes.versions.units.elements.edit', [$scheme, $version, $unit, $element]) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.schemes.versions.units.elements.destroy', [$scheme, $version, $unit, $element]) }}" method="POST" onsubmit="return confirm('Delete this element?')">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">inventory_2</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No elements yet</h3>
                <p class="text-gray-600 mb-6">Add elements to define competency criteria</p>
                <a href="{{ route('admin.schemes.versions.units.elements.create', [$scheme, $version, $unit]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add First Element</span>
                </a>
            </div>
        @endif

        <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Back to Version</span>
        </a>
    </div>
@endsection
