@extends('layouts.admin')

@section('title', 'Certification Schemes Management')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Certification Schemes Management')
@section('page_description', 'Manage certification schemes and competency standards')

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
                    <h2 class="text-lg font-bold text-gray-900">Schemes List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $schemes->total() }} schemes</p>
                </div>
                <a href="{{ route('admin.schemes.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Scheme</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.schemes.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                    <div class="md:col-span-2 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by code, name, or title..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <select name="type" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">All Types</option>
                        <option value="occupation" {{ ($type ?? '') == 'occupation' ? 'selected' : '' }}>Occupation</option>
                        <option value="cluster" {{ ($type ?? '') == 'cluster' ? 'selected' : '' }}>Cluster</option>
                        <option value="qualification" {{ ($type ?? '') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                    </select>
                    <select name="sector" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">All Sectors</option>
                        @foreach($sectors as $sectorOption)
                            <option value="{{ $sectorOption }}" {{ ($sector ?? '') == $sectorOption ? 'selected' : '' }}>{{ $sectorOption }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            Search
                        </button>
                        @if($search || $type || $sector || $status)
                            <a href="{{ route('admin.schemes.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sector</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($schemes as $scheme)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-mono font-semibold text-gray-900">{{ $scheme->code }}</p>
                                @if(!$scheme->is_active)
                                    <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $scheme->name }}</p>
                                @if($scheme->occupation_title)
                                    <p class="text-xs text-gray-500 mt-1">{{ $scheme->occupation_title }}</p>
                                @endif
                                @if($scheme->creator)
                                    <p class="text-xs text-gray-400 mt-1">
                                        <span class="material-symbols-outlined text-xs align-middle">person</span>
                                        By {{ $scheme->creator->name }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4">
                                @if($scheme->qualification_level)
                                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                        KKNI {{ $scheme->qualification_level }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($scheme->sector)
                                    <p class="text-sm text-gray-900">{{ $scheme->sector }}</p>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($scheme->status)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        {{ $scheme->status->label }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.schemes.show', $scheme) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.schemes.edit', $scheme) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.schemes.destroy', $scheme) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this scheme?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">description</span>
                                <p class="text-gray-500">No schemes found</p>
                                @if($search || $type || $sector || $status)
                                    <a href="{{ route('admin.schemes.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                                        Clear filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schemes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schemes->links() }}
            </div>
        @endif
    </div>
@endsection
