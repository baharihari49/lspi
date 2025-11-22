@extends('layouts.admin')

@section('title', 'TUK Facilities Management')

@php
    $active = 'tuk-facilities';
@endphp

@section('page_title', 'TUK Facilities Management')
@section('page_description', 'Manage facilities and equipment for Tempat Uji Kompetensi')

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
                    <h2 class="text-lg font-bold text-gray-900">Facilities List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $facilities->total() }} facilities</p>
                </div>
                <a href="{{ route('admin.tuk-facilities.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Facility</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.tuk-facilities.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by facility name, category, or description..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <select name="tuk_id" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">All TUK</option>
                        @foreach($tuks as $tuk)
                            <option value="{{ $tuk->id }}" {{ ($tukId ?? '') == $tuk->id ? 'selected' : '' }}>
                                {{ $tuk->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if($search || $tukId)
                        <a href="{{ route('admin.tuk-facilities.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUK</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Facility Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Condition</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Next Maintenance</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($facilities as $facility)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $facility->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $facility->tuk->code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                                @if($facility->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($facility->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $categoryColors = [
                                        'technology' => 'bg-blue-100 text-blue-700',
                                        'equipment' => 'bg-green-100 text-green-700',
                                        'furniture' => 'bg-yellow-100 text-yellow-700',
                                        'safety' => 'bg-red-100 text-red-700',
                                        'other' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $categoryColors[$facility->category] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($facility->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900">{{ $facility->quantity }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $conditionColors = [
                                        'excellent' => 'bg-green-100 text-green-700',
                                        'good' => 'bg-blue-100 text-blue-700',
                                        'fair' => 'bg-yellow-100 text-yellow-700',
                                        'poor' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $conditionColors[$facility->condition] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($facility->condition) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($facility->next_maintenance)
                                    <p class="text-sm font-semibold text-gray-900">{{ $facility->next_maintenance->format('d M Y') }}</p>
                                    @if($facility->next_maintenance->isPast())
                                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Overdue</span>
                                    @elseif($facility->next_maintenance->lte(now()->addDays(30)))
                                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Due Soon</span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">Not scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tuk-facilities.edit', $facility) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk-facilities.destroy', $facility) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this facility?')">
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
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">inventory_2</span>
                                <p class="text-gray-500">No facilities found</p>
                                @if($search || $tukId)
                                    <a href="{{ route('admin.tuk-facilities.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                                        Clear filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($facilities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>
@endsection
