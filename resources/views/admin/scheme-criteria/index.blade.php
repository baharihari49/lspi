@extends('layouts.admin')

@section('title', 'Criteria - ' . $element->title)

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Kriteria Unjuk Kerja (KUK)')
@section('page_description', $element->code . ' - ' . $element->title)

@section('content')
    <div class="w-full space-y-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit]) }}" class="hover:text-blue-900">{{ $unit->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">{{ $element->code }}</span>
        </nav>

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $element->title }}</h2>
                <p class="text-gray-600 mt-1">{{ $unit->code }} / {{ $element->code }}</p>
            </div>
            <a href="{{ route('admin.schemes.versions.units.elements.criteria.create', [$scheme, $version, $unit, $element]) }}" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">add</span>
                <span>Add Criterion</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($element->criteria->count() > 0)
            <div class="space-y-3">
                @foreach($element->criteria as $criterion)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($criterion->code)
                                        <span class="font-mono text-sm text-gray-600">{{ $criterion->code }}</span>
                                    @endif
                                    @if($criterion->assessment_method)
                                        @php
                                            $methodColors = [
                                                'written' => 'bg-blue-100 text-blue-700',
                                                'practical' => 'bg-green-100 text-green-700',
                                                'oral' => 'bg-purple-100 text-purple-700',
                                                'portfolio' => 'bg-yellow-100 text-yellow-700',
                                                'observation' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 {{ $methodColors[$criterion->assessment_method] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs font-semibold">
                                            {{ ucfirst($criterion->assessment_method) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-900">{{ $criterion->description }}</p>
                                @if($criterion->evidence_guide)
                                    <p class="text-sm text-gray-600 mt-2">
                                        <span class="font-semibold">Evidence:</span> {{ $criterion->evidence_guide }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('admin.schemes.versions.units.elements.criteria.edit', [$scheme, $version, $unit, $element, $criterion]) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.schemes.versions.units.elements.criteria.destroy', [$scheme, $version, $unit, $element, $criterion]) }}" method="POST" onsubmit="return confirm('Delete this criterion?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">checklist</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No criteria yet</h3>
                <p class="text-gray-600 mb-6">Add KUK (Kriteria Unjuk Kerja) for this element</p>
                <a href="{{ route('admin.schemes.versions.units.elements.criteria.create', [$scheme, $version, $unit, $element]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add First Criterion</span>
                </a>
            </div>
        @endif

        <a href="{{ route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Back to Elements</span>
        </a>
    </div>
@endsection
