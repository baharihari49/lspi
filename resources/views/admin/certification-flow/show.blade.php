@extends('layouts.admin')

@section('title', 'Certification Flow - ' . $apl01->form_number)

@php
    $active = 'certification-flow';
@endphp

@section('page_title', $apl01->form_number)
@section('page_description', 'Track certification progress for this application')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Application Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Application Information</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">conversion_path</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $apl01->form_number }}</h4>
                            <p class="text-sm text-gray-600">{{ $apl01->assessee->full_name ?? $apl01->full_name }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @php
                                    $stageColors = [
                                        'apl01_pending' => 'bg-gray-100 text-gray-700',
                                        'apl01_approved' => 'bg-blue-100 text-blue-700',
                                        'apl02_generated' => 'bg-yellow-100 text-yellow-700',
                                        'apl02_in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'apl02_completed' => 'bg-purple-100 text-purple-700',
                                        'assessment_scheduled' => 'bg-indigo-100 text-indigo-700',
                                        'assessment_in_progress' => 'bg-indigo-100 text-indigo-700',
                                        'assessment_completed' => 'bg-purple-100 text-purple-700',
                                        'assessment_approved' => 'bg-green-100 text-green-700',
                                        'certificate_issued' => 'bg-green-100 text-green-700',
                                    ];
                                    $stageLabels = [
                                        'apl01_pending' => 'Menunggu',
                                        'apl01_approved' => 'APL-01 Disetujui',
                                        'apl02_generated' => 'APL-02 Dibuat',
                                        'apl02_in_progress' => 'APL-02 Dalam Proses',
                                        'apl02_completed' => 'APL-02 Selesai',
                                        'assessment_scheduled' => 'Asesmen Terjadwal',
                                        'assessment_in_progress' => 'Asesmen Berlangsung',
                                        'assessment_completed' => 'Asesmen Selesai',
                                        'assessment_approved' => 'Asesmen Disetujui',
                                        'certificate_issued' => 'Sertifikat Terbit',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $stageColors[$flowStatus['current_stage']] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ $stageLabels[$flowStatus['current_stage']] ?? ucfirst(str_replace('_', ' ', $flowStatus['current_stage'])) }}
                                </span>
                                @if($flowStatus['apl01']['approved'])
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">APL-01 Approved</span>
                                @endif
                                @if($flowStatus['apl02']['all_complete'])
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">APL-02 Complete</span>
                                @endif
                                @if($flowStatus['certificate']['exists'])
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Certified</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessee</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->assessee->full_name ?? $apl01->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $apl01->assessee->assessee_number ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">workspace_premium</span>
                            <div>
                                <p class="text-xs text-gray-600">Scheme</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->scheme->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $apl01->scheme->code ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Application Date</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        @if($flowStatus['certificate']['exists'])
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">verified</span>
                                <div>
                                    <p class="text-xs text-gray-600">Certificate Number</p>
                                    <p class="font-semibold text-gray-900">{{ $flowStatus['certificate']['number'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Sertifikasi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Progress Sertifikasi</h3>

                @php
                    $progress = 0;
                    if ($flowStatus['apl01']['approved']) $progress = 20;
                    if ($flowStatus['apl02']['generated']) $progress = 40;
                    if ($flowStatus['apl02']['all_complete']) $progress = 50;
                    if ($flowStatus['assessment']['exists']) $progress = 60;
                    if ($flowStatus['assessment']['approved']) $progress = 80;
                    if ($flowStatus['certificate']['exists']) $progress = 100;
                @endphp

                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Overall Progress</span>
                        <span class="font-bold text-gray-900">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <!-- Steps -->
                <div class="grid grid-cols-5 gap-2">
                    <!-- Step 1: APL-01 -->
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $flowStatus['apl01']['approved'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <span class="material-symbols-outlined">description</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 mt-2">APL-01</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                        @if($flowStatus['apl01']['approved'])
                            <span class="text-xs text-green-600 flex items-center justify-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                            </span>
                        @endif
                    </div>

                    <!-- Step 2: APL-02 -->
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $flowStatus['apl02']['all_complete'] ? 'bg-green-500 text-white' : ($flowStatus['apl02']['generated'] ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                            <span class="material-symbols-outlined">assignment</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 mt-2">APL-02</p>
                        <p class="text-xs text-gray-500">Asesmen Mandiri</p>
                        @if($flowStatus['apl02']['total_units'] > 0)
                            <span class="text-xs {{ $flowStatus['apl02']['all_complete'] ? 'text-green-600' : 'text-yellow-600' }} mt-1 block">
                                {{ $flowStatus['apl02']['competent_units'] }}/{{ $flowStatus['apl02']['total_units'] }}
                            </span>
                        @endif
                    </div>

                    <!-- Step 3: Assessment -->
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $flowStatus['assessment']['approved'] ? 'bg-green-500 text-white' : ($flowStatus['assessment']['exists'] ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 mt-2">Asesmen</p>
                        <p class="text-xs text-gray-500">Penilaian</p>
                        @if($flowStatus['assessment']['scheduled_date'])
                            <span class="text-xs text-gray-500 mt-1 block">{{ $flowStatus['assessment']['scheduled_date']->format('d M') }}</span>
                        @endif
                    </div>

                    <!-- Step 4: Result -->
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $flowStatus['assessment']['approved'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 mt-2">Hasil</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                        @if($flowStatus['assessment']['result'])
                            <span class="text-xs {{ $flowStatus['assessment']['result'] === 'competent' ? 'text-green-600' : 'text-red-600' }} mt-1 block">
                                {{ ucfirst($flowStatus['assessment']['result']) }}
                            </span>
                        @endif
                    </div>

                    <!-- Step 5: Certificate -->
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $flowStatus['certificate']['exists'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <span class="material-symbols-outlined">workspace_premium</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 mt-2">Sertifikat</p>
                        <p class="text-xs text-gray-500">Diterbitkan</p>
                        @if($flowStatus['certificate']['exists'])
                            <span class="text-xs text-green-600 flex items-center justify-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- APL-01 Detail -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">description</span>
                            APL-01
                        </h3>
                        <a href="{{ route('admin.apl01.show', $apl01) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Detail &rarr;
                        </a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Form Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['apl01']['form_number'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $flowStatus['apl01']['approved'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($flowStatus['apl01']['status']) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- APL-02 Detail -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-yellow-600">assignment</span>
                            APL-02
                        </h3>
                        @if($flowStatus['apl02']['generated'])
                            <a href="{{ route('admin.apl02.units.index', ['apl01_form_id' => $apl01->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Units &rarr;
                            </a>
                        @endif
                    </div>
                    @if($flowStatus['apl02']['generated'])
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Units</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['apl02']['total_units'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Kompeten</span>
                                <span class="text-sm font-semibold text-green-600">{{ $flowStatus['apl02']['competent_units'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Belum Kompeten</span>
                                <span class="text-sm font-semibold text-red-600">{{ $flowStatus['apl02']['not_yet_competent_units'] }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold">{{ $flowStatus['apl02']['progress_percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $flowStatus['apl02']['progress_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">APL-02 belum dibuat.</p>
                    @endif
                </div>

                <!-- Assessment Detail -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-600">event</span>
                            Assessment
                        </h3>
                        @if($flowStatus['assessment']['exists'])
                            <a href="{{ route('admin.assessments.show', $flowStatus['assessment']['id']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail &rarr;
                            </a>
                        @endif
                    </div>
                    @if($flowStatus['assessment']['exists'])
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Number</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['assessment']['number'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Scheduled</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['assessment']['scheduled_date'] ? $flowStatus['assessment']['scheduled_date']->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $flowStatus['assessment']['approved'] ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($flowStatus['assessment']['status']) }}
                                </span>
                            </div>
                            @if($flowStatus['assessment']['result'])
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Result</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $flowStatus['assessment']['result'] === 'competent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($flowStatus['assessment']['result']) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Assessment belum dijadwalkan.</p>
                    @endif
                </div>

                <!-- Certificate Detail -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600">workspace_premium</span>
                            Certificate
                        </h3>
                        @if($flowStatus['certificate']['exists'])
                            <a href="{{ route('admin.certificates.show', $flowStatus['certificate']['id']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail &rarr;
                            </a>
                        @endif
                    </div>
                    @if($flowStatus['certificate']['exists'])
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Number</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['certificate']['number'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Issue Date</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['certificate']['issued_at'] ? $flowStatus['certificate']['issued_at']->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Valid Until</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $flowStatus['certificate']['valid_until'] ? $flowStatus['certificate']['valid_until']->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    {{ ucfirst($flowStatus['certificate']['status']) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Certificate belum diterbitkan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if(!$flowStatus['apl02']['generated'] && $flowStatus['apl01']['approved'])
                        <form action="{{ route('admin.certification-flow.generate-apl02', $apl01) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                Generate APL-02
                            </button>
                        </form>
                    @endif

                    @if($flowStatus['apl02']['all_complete'] && !$flowStatus['assessment']['exists'])
                        <form action="{{ route('admin.certification-flow.schedule-assessment', $apl01) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">event</span>
                                Schedule Assessment
                            </button>
                        </form>
                    @endif

                    @if($flowStatus['assessment']['approved'] && $flowStatus['assessment']['result'] === 'competent' && !$flowStatus['certificate']['exists'])
                        <form action="{{ route('admin.certification-flow.generate-certificate', $flowStatus['assessment']['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">workspace_premium</span>
                                Generate Certificate
                            </button>
                        </form>
                    @endif

                    @if($flowStatus['certificate']['exists'])
                        <a href="{{ route('admin.certificates.download', $flowStatus['certificate']['id']) }}" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">download</span>
                            Download Certificate
                        </a>
                    @endif

                    <a href="{{ route('admin.apl01.show', $apl01) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View APL-01
                    </a>

                    <a href="{{ route('admin.certification-flow.dashboard') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to Dashboard
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Application Date:</span><br>
                        {{ $apl01->created_at->format('d M Y H:i') }}
                    </p>
                    @if($flowStatus['apl02']['generated_at'])
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">APL-02 Generated:</span><br>
                            {{ $flowStatus['apl02']['generated_at']->format('d M Y H:i') }}
                        </p>
                    @endif
                    @if($flowStatus['certificate']['issued_at'])
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Certificate Issued:</span><br>
                            {{ $flowStatus['certificate']['issued_at']->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Status Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status Summary</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">APL-01</span>
                        <span class="font-bold {{ $flowStatus['apl01']['approved'] ? 'text-green-600' : 'text-gray-900' }}">
                            {{ $flowStatus['apl01']['approved'] ? 'Approved' : 'Pending' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">APL-02</span>
                        <span class="font-bold {{ $flowStatus['apl02']['all_complete'] ? 'text-green-600' : ($flowStatus['apl02']['generated'] ? 'text-yellow-600' : 'text-gray-900') }}">
                            @if($flowStatus['apl02']['all_complete'])
                                Complete
                            @elseif($flowStatus['apl02']['generated'])
                                In Progress
                            @else
                                Not Started
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Assessment</span>
                        <span class="font-bold {{ $flowStatus['assessment']['approved'] ? 'text-green-600' : ($flowStatus['assessment']['exists'] ? 'text-yellow-600' : 'text-gray-900') }}">
                            @if($flowStatus['assessment']['approved'])
                                Approved
                            @elseif($flowStatus['assessment']['exists'])
                                {{ ucfirst($flowStatus['assessment']['status']) }}
                            @else
                                Not Scheduled
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Certificate</span>
                        <span class="font-bold {{ $flowStatus['certificate']['exists'] ? 'text-green-600' : 'text-gray-900' }}">
                            {{ $flowStatus['certificate']['exists'] ? 'Issued' : 'Not Issued' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
