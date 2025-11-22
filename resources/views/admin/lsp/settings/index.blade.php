@extends('layouts.admin')

@section('title', 'Organization Settings')

@php
    $active = 'org-settings';
@endphp

@section('page_title', 'Organization Settings')
@section('page_description', 'Manage organization configuration settings grouped by category')

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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Settings List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $settings->total() }} settings</p>
                </div>
                <a href="{{ route('admin.org-settings.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Setting</span>
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.org-settings.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Search -->
                    <div class="md:col-span-2 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by key, value, or description..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <!-- Group Filter -->
                    <div>
                        <select name="group" class="w-full h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">All Groups</option>
                            @foreach($groups as $groupName)
                                @if($groupName)
                                    <option value="{{ $groupName }}" {{ ($group ?? '') == $groupName ? 'selected' : '' }}>
                                        {{ ucfirst($groupName) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 mt-3">
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Apply Filters
                    </button>
                    @if($search || $group)
                        <a href="{{ route('admin.org-settings.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Settings List Grouped by Category -->
        @php
            $settingsByGroup = $settings->groupBy('group');
        @endphp

        @forelse($settingsByGroup as $groupName => $groupSettings)
            <div class="border-b border-gray-200 last:border-b-0">
                <!-- Group Header -->
                <div class="px-6 py-3 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">
                        {{ $groupName ?: 'General' }}
                    </h3>
                </div>

                <!-- Settings in Group -->
                <div class="divide-y divide-gray-100">
                    @foreach($groupSettings as $setting)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $setting->label ?: $setting->key }}</h4>

                                        <!-- Type Badge -->
                                        @php
                                            $typeColors = [
                                                'string' => 'bg-blue-100 text-blue-700',
                                                'number' => 'bg-green-100 text-green-700',
                                                'boolean' => 'bg-purple-100 text-purple-700',
                                                'json' => 'bg-orange-100 text-orange-700',
                                                'date' => 'bg-pink-100 text-pink-700',
                                            ];
                                        @endphp
                                        <span class="inline-block px-2 py-0.5 {{ $typeColors[$setting->type] ?? 'bg-gray-100 text-gray-600' }} rounded text-xs font-semibold">
                                            {{ strtoupper($setting->type) }}
                                        </span>

                                        <!-- Public Badge -->
                                        @if($setting->is_public)
                                            <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
                                                PUBLIC
                                            </span>
                                        @endif

                                        <!-- Not Editable Badge -->
                                        @if(!$setting->is_editable)
                                            <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                                LOCKED
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-500 font-mono mb-1">{{ $setting->key }}</p>

                                    @if($setting->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $setting->description }}</p>
                                    @endif

                                    <div class="flex items-start gap-2">
                                        <span class="text-xs text-gray-500 mt-0.5">Value:</span>
                                        <div class="flex-1">
                                            @if($setting->type === 'boolean')
                                                @if($setting->value)
                                                    <span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                                        TRUE
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">
                                                        FALSE
                                                    </span>
                                                @endif
                                            @elseif($setting->type === 'json')
                                                <pre class="text-xs bg-gray-50 p-2 rounded font-mono overflow-x-auto">{{ json_encode($setting->value, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                <p class="text-sm font-semibold text-gray-900">{{ $setting->getRawOriginal('value') ?: '(empty)' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($setting->is_editable)
                                        <a href="{{ route('admin.org-settings.edit', $setting) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.org-settings.destroy', $setting) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.org-settings.edit', $setting) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">settings</span>
                <p class="text-gray-500">No settings found</p>
                @if($search || $group)
                    <a href="{{ route('admin.org-settings.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                        Clear filters
                    </a>
                @endif
            </div>
        @endforelse

        @if($settings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $settings->links() }}
            </div>
        @endif
    </div>
@endsection
