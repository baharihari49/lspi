@extends('layouts.admin')

@section('title', 'LSP Profiles')

@php
    $active = 'lsp-profiles';
@endphp

@section('page_title', 'LSP Profiles')
@section('page_description', 'Manage LSP organization profiles and information')

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
                    <h2 class="text-lg font-bold text-gray-900">LSP Profiles List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $profiles->total() }} profiles</p>
                </div>
                <a href="{{ route('admin.lsp-profiles.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add LSP Profile</span>
                </a>
            </div>

            <!-- Search Box -->
            <form method="GET" action="{{ route('admin.lsp-profiles.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by code, name, or license number..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('admin.lsp-profiles.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">License Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">License Expiry</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($profiles as $profile)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900 font-mono">{{ $profile->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $profile->name }}</p>
                                @if($profile->legal_name)
                                    <p class="text-xs text-gray-500 mt-1">{{ $profile->legal_name }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900 font-mono">{{ $profile->license_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($profile->license_expiry_date)
                                    <p class="text-sm text-gray-900">{{ $profile->license_expiry_date->format('d M Y') }}</p>
                                    @if($profile->license_expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            Expired
                                        </span>
                                    @elseif($profile->license_expiry_date->lte(now()->addDays(30)))
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            Expiring Soon
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($profile->status)
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $profile->status->label }}
                                    </span>
                                @endif
                                @if($profile->is_active)
                                    <span class="block mt-1 px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                        Active
                                    </span>
                                @else
                                    <span class="block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.lsp-profiles.show', $profile) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.lsp-profiles.edit', $profile) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.lsp-profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this profile?')">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">corporate_fare</span>
                                <p class="text-gray-500">No LSP profiles found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($profiles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $profiles->links() }}
            </div>
        @endif
    </div>
@endsection
