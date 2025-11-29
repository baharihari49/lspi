@extends('layouts.admin')

@section('title', 'Version ' . $version->version . ' - ' . $scheme->name)

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Version ' . $version->version)
@section('page_description', $scheme->code . ' - ' . $scheme->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.schemes.versions.index', $scheme) }}" class="hover:text-blue-900">Versions</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">{{ $version->version }}</span>
        </nav>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Version Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Version Information</h3>
                        <a href="{{ route('admin.schemes.versions.edit', [$scheme, $version]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Version</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $version->version }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Status</p>
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
                        </div>
                        @if($version->effective_date)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Effective Date</p>
                            <p class="text-sm text-gray-900">{{ $version->effective_date->format('d M Y') }}</p>
                        </div>
                        @endif
                        @if($version->expiry_date)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Expiry Date</p>
                            <p class="text-sm text-gray-900">{{ $version->expiry_date->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($version->changes_summary)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Changes Summary</p>
                        <p class="text-sm text-gray-700">{{ $version->changes_summary }}</p>
                    </div>
                    @endif

                    @if($version->approved_at)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="material-symbols-outlined text-sm text-green-600 align-middle">verified</span>
                            Approved by <span class="font-semibold">{{ $version->approver?->name ?? 'Unknown' }}</span>
                            on {{ $version->approved_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Competency Units -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Competency Units</h3>
                        <a href="{{ route('admin.schemes.versions.units.create', [$scheme, $version]) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Add Unit</span>
                        </a>
                    </div>

                    @if($version->units->count() > 0)
                        <div class="space-y-3">
                            @foreach($version->units as $unit)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
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
                                            <p class="font-semibold text-gray-900">{{ $unit->title }}</p>
                                            @if($unit->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $unit->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                @if($unit->credit_hours)
                                                    <span><span class="material-symbols-outlined text-sm align-middle">schedule</span> {{ $unit->credit_hours }} hours</span>
                                                @endif
                                                <span><span class="material-symbols-outlined text-sm align-middle">folder</span> {{ $unit->elements->count() }} elements</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Manage Elements">
                                                <span class="material-symbols-outlined">inventory_2</span>
                                            </a>
                                            <a href="{{ route('admin.schemes.versions.units.edit', [$scheme, $version, $unit]) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <form action="{{ route('admin.schemes.versions.units.destroy', [$scheme, $version, $unit]) }}" method="POST" onsubmit="return confirm('Delete this unit and all its elements?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Elements Preview -->
                                    @if($unit->elements->count() > 0)
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <p class="text-xs text-gray-500 mb-2">Elements:</p>
                                            <div class="space-y-1">
                                                @foreach($unit->elements as $element)
                                                    <div class="text-xs text-gray-700">
                                                        <span class="font-mono text-gray-500">{{ $element->code }}.</span>
                                                        {{ $element->title }}
                                                        <span class="text-gray-400">({{ $element->criteria->count() }} KUK)</span>
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
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">folder_open</span>
                            <p class="text-gray-500">No units added yet</p>
                        </div>
                    @endif
                </div>

                <!-- KUK Overview (All Criteria) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">KUK Overview (Kriteria Unjuk Kerja)</h3>
                        <a href="{{ route('admin.kuk.index', ['scheme_id' => $scheme->id]) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-900 rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">open_in_new</span>
                            <span>Lihat Semua KUK</span>
                        </a>
                    </div>

                    @php
                        $allKuk = [];
                        foreach ($version->units as $unit) {
                            foreach ($unit->elements as $element) {
                                foreach ($element->criteria as $criterion) {
                                    $allKuk[] = [
                                        'unit' => $unit,
                                        'element' => $element,
                                        'criterion' => $criterion,
                                    ];
                                }
                            }
                        }
                    @endphp

                    @if(count($allKuk) > 0)
                        <div class="space-y-4">
                            @foreach($version->units as $unit)
                                @if($unit->elements->sum(fn($e) => $e->criteria->count()) > 0)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <button type="button" onclick="toggleKukUnit('kuk-unit-{{ $unit->id }}')" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition text-left">
                                            <div class="flex items-center gap-3">
                                                <span class="material-symbols-outlined text-blue-900">folder</span>
                                                <div>
                                                    <p class="font-mono text-xs text-gray-600">{{ $unit->code }}</p>
                                                    <p class="font-semibold text-gray-900">{{ Str::limit($unit->title, 50) }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm text-gray-500">{{ $unit->elements->sum(fn($e) => $e->criteria->count()) }} KUK</span>
                                                <span class="material-symbols-outlined text-gray-400 transition-transform kuk-arrow" id="kuk-arrow-{{ $unit->id }}">expand_more</span>
                                            </div>
                                        </button>
                                        <div id="kuk-unit-{{ $unit->id }}" class="hidden">
                                            @foreach($unit->elements as $element)
                                                @if($element->criteria->count() > 0)
                                                    <div class="border-t border-gray-200">
                                                        <div class="p-3 bg-blue-50 flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-sm text-blue-700">description</span>
                                                                <span class="text-sm font-semibold text-blue-900">{{ $element->code }} - {{ Str::limit($element->title, 40) }}</span>
                                                            </div>
                                                            <a href="{{ route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element]) }}" class="text-xs text-blue-700 hover:text-blue-900 flex items-center gap-1">
                                                                <span>Manage</span>
                                                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                                            </a>
                                                        </div>
                                                        <div class="divide-y divide-gray-100">
                                                            @foreach($element->criteria as $criterion)
                                                                <div class="p-3 pl-8 flex items-start justify-between hover:bg-gray-50 transition">
                                                                    <div class="flex-1">
                                                                        <div class="flex items-center gap-2">
                                                                            @if($criterion->code)
                                                                                <span class="font-mono text-xs text-gray-600">{{ $criterion->code }}</span>
                                                                            @endif
                                                                            @if($criterion->assessment_method)
                                                                                @php
                                                                                    $methodColors = [
                                                                                        'written' => 'bg-blue-100 text-blue-700',
                                                                                        'practical' => 'bg-green-100 text-green-700',
                                                                                        'oral' => 'bg-yellow-100 text-yellow-700',
                                                                                        'portfolio' => 'bg-purple-100 text-purple-700',
                                                                                        'observation' => 'bg-orange-100 text-orange-700',
                                                                                    ];
                                                                                @endphp
                                                                                <span class="px-2 py-0.5 {{ $methodColors[$criterion->assessment_method] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs">
                                                                                    {{ ucfirst($criterion->assessment_method) }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <p class="text-sm text-gray-700 mt-1">{{ Str::limit($criterion->description, 100) }}</p>
                                                                    </div>
                                                                    <a href="{{ route('admin.schemes.versions.units.elements.criteria.edit', [$scheme, $version, $unit, $element, $criterion]) }}" class="p-1 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                                                                        <span class="material-symbols-outlined text-lg">edit</span>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">checklist</span>
                            <p class="text-gray-500">Belum ada KUK</p>
                            <p class="text-sm text-gray-400 mt-1">Tambahkan elements dan criteria pada unit kompetensi</p>
                        </div>
                    @endif
                </div>

                <!-- Requirements -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Requirements</h3>
                        <a href="{{ route('admin.schemes.versions.requirements.create', [$scheme, $version]) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Add Requirement</span>
                        </a>
                    </div>

                    @if($version->requirements->count() > 0)
                        <div class="space-y-2">
                            @foreach($version->requirements as $requirement)
                                <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-semibold text-gray-900">{{ $requirement->title }}</p>
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
                                            @if($requirement->is_mandatory)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">Mandatory</span>
                                            @endif
                                        </div>
                                        @if($requirement->description)
                                            <p class="text-sm text-gray-600">{{ $requirement->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <a href="{{ route('admin.schemes.versions.requirements.edit', [$scheme, $version, $requirement]) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form action="{{ route('admin.schemes.versions.requirements.destroy', [$scheme, $version, $requirement]) }}" method="POST" onsubmit="return confirm('Delete this requirement?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">rule</span>
                            <p class="text-gray-500">No requirements added yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions & Stats -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Units</span>
                                <span class="font-bold text-gray-900">{{ $version->units->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Elements</span>
                                <span class="font-bold text-gray-900">{{ $version->units->sum(fn($u) => $u->elements->count()) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Criteria (KUK)</span>
                                <span class="font-bold text-gray-900">{{ $version->units->sum(fn($u) => $u->elements->sum(fn($e) => $e->criteria->count())) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Requirements</span>
                                <span class="font-bold text-gray-900">{{ $version->requirements->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                        <div class="space-y-2">
                            @if(!$version->is_current && $version->status === 'active')
                                <form action="{{ route('admin.schemes.versions.set-current', [$scheme, $version]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition text-sm">
                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                        <span>Set as Current</span>
                                    </button>
                                </form>
                            @endif

                            @if($version->status === 'draft' && !$version->approved_at)
                                <form action="{{ route('admin.schemes.versions.approve', [$scheme, $version]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition text-sm">
                                        <span class="material-symbols-outlined text-lg">verified</span>
                                        <span>Approve Version</span>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.schemes.versions.edit', [$scheme, $version]) }}" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-lg">edit</span>
                                <span>Edit Version</span>
                            </a>

                            <a href="{{ route('admin.schemes.versions.index', $scheme) }}" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-lg">arrow_back</span>
                                <span>Back to Versions</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleKukUnit(unitId) {
            const content = document.getElementById(unitId);
            const arrow = document.getElementById('kuk-arrow-' + unitId.replace('kuk-unit-', ''));

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
