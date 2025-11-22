@extends('layouts.admin')

@section('title', 'TUK Management')

@php
    $active = 'tuk';
@endphp

@section('page_title', 'TUK Management')
@section('page_description', 'Manage Tempat Uji Kompetensi (Competency Test Centers)')

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
                    <h2 class="text-lg font-bold text-gray-900">TUK List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $tuks->total() }} test centers</p>
                </div>
                <a href="{{ route('admin.tuk.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add TUK</span>
                </a>
            </div>

            <!-- Search Box -->
            <form method="GET" action="{{ route('admin.tuk.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by code, name, or location..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('admin.tuk.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tuks as $tuk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900 font-mono">{{ $tuk->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $tuk->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $tuk->facilities_count }} facilities •
                                    {{ $tuk->documents_count }} docs •
                                    {{ $tuk->schedules_count }} schedules
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'permanent' => 'bg-green-100 text-green-700',
                                        'temporary' => 'bg-yellow-100 text-yellow-700',
                                        'mobile' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $typeColors[$tuk->type] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($tuk->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $tuk->city }}</p>
                                @if($tuk->province)
                                    <p class="text-xs text-gray-500 mt-1">{{ $tuk->province }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900">{{ $tuk->capacity }}</span>
                                <span class="text-xs text-gray-500">people</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($tuk->manager)
                                    <span class="text-sm text-gray-900">{{ $tuk->manager->name }}</span>
                                @else
                                    <span class="text-xs text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($tuk->is_active)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tuk.show', $tuk) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.tuk.edit', $tuk) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk.destroy', $tuk) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this TUK?')">
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
                            <td colspan="8" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">location_city</span>
                                <p class="text-gray-500">No TUK found</p>
                                @if($search)
                                    <a href="{{ route('admin.tuk.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                                        Clear search
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tuks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tuks->links() }}
            </div>
        @endif
    </div>
@endsection
