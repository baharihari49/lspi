@extends('layouts.admin')

@section('title', 'Modules')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modules</h1>
            <p class="text-gray-600 mt-1">Akses cepat ke semua modul aplikasi</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <span class="material-symbols-outlined text-lg">schedule</span>
            <span>{{ now()->format('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Module Groups -->
    @foreach($moduleGroups as $group)
        <div class="space-y-4">
            <!-- Group Header -->
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-{{ $group['color'] }}-200 to-transparent"></div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span>{{ $group['title'] }}</span>
                </h2>
                <div class="h-px flex-1 bg-gradient-to-l from-{{ $group['color'] }}-200 to-transparent"></div>
            </div>
            <p class="text-sm text-gray-500 text-center -mt-2">{{ $group['description'] }}</p>

            <!-- Module Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($group['modules'] as $module)
                    @if(Route::has($module['route']))
                        <a href="{{ route($module['route']) }}"
                           class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg hover:border-{{ $module['color'] }}-300 hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                            <!-- Background Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-br from-{{ $module['color'] }}-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                            <!-- Content -->
                            <div class="relative">
                                <!-- Icon -->
                                <div class="w-14 h-14 rounded-xl bg-{{ $module['color'] }}-100 text-{{ $module['color'] }}-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">{{ $module['icon'] }}</span>
                                </div>

                                <!-- Name & Description -->
                                <h3 class="font-bold text-gray-900 group-hover:text-{{ $module['color'] }}-700 transition-colors">
                                    {{ $module['name'] }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $module['description'] }}</p>

                                <!-- Stats -->
                                @if($module['stats'] !== null)
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <span class="text-2xl font-bold text-gray-900">{{ number_format($module['stats']) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">data</span>
                                    </div>
                                @endif

                                <!-- Badge -->
                                @if($module['badge'])
                                    <div class="absolute top-0 right-0">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $module['badge_color'] ?? 'gray' }}-100 text-{{ $module['badge_color'] ?? 'gray' }}-800">
                                            {{ $module['badge'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Arrow indicator -->
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-{{ $module['color'] }}-400">arrow_forward</span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Quick Stats Summary -->
    <div class="mt-8 bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-6 text-white">
        <h3 class="text-lg font-bold mb-4">Ringkasan Statistik</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Total APL-01</p>
                <p class="text-3xl font-bold">{{ number_format($stats['apl01']) }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Total APL-02</p>
                <p class="text-3xl font-bold">{{ number_format($stats['apl02_units']) }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Assessments</p>
                <p class="text-3xl font-bold">{{ number_format($stats['assessments']) }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Certificates</p>
                <p class="text-3xl font-bold">{{ number_format($stats['certificates']) }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Assessees</p>
                <p class="text-3xl font-bold">{{ number_format($stats['assessees']) }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-sm">Assessors</p>
                <p class="text-3xl font-bold">{{ number_format($stats['assessors']) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
