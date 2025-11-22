@extends('layouts.admin')

@section('title', 'TUK Schedules Management')

@php
    $active = 'tuk-schedules';
@endphp

@section('page_title', 'TUK Schedules Management')
@section('page_description', 'Manage schedules for Tempat Uji Kompetensi')

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
                    <h2 class="text-lg font-bold text-gray-900">Schedules List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $schedules->total() }} schedules</p>
                </div>
                <a href="{{ route('admin.tuk-schedules.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Schedule</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.tuk-schedules.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search schedules..."
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
                    <select name="status" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">All Status</option>
                        <option value="available" {{ ($status ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="booked" {{ ($status ?? '') == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="blocked" {{ ($status ?? '') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        <option value="maintenance" {{ ($status ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if($search || $tukId || $status)
                        <a href="{{ route('admin.tuk-schedules.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50 {{ $schedule->date->isPast() && $schedule->status === 'available' ? 'bg-gray-50' : '' }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $schedule->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $schedule->tuk->code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $schedule->date->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $schedule->date->diffForHumans() }}</p>
                                @if($schedule->date->isPast() && $schedule->status === 'available')
                                    <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Past</span>
                                @elseif($schedule->date->isToday())
                                    <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Today</span>
                                @elseif($schedule->date->isTomorrow())
                                    <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Tomorrow</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">
                                    <span class="material-symbols-outlined text-sm align-middle">schedule</span>
                                    {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->diffInHours(\Carbon\Carbon::parse($schedule->end_time)) }} hours
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-700',
                                        'booked' => 'bg-blue-100 text-blue-700',
                                        'blocked' => 'bg-red-100 text-red-700',
                                        'maintenance' => 'bg-yellow-100 text-yellow-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($schedule->status === 'available' || $schedule->status === 'booked')
                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                        {{ $schedule->available_capacity ?? 0 }} / {{ $schedule->tuk->capacity }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $schedule->tuk->capacity > 0 ? (($schedule->available_capacity ?? 0) / $schedule->tuk->capacity * 100) : 0 }}%"></div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Not applicable</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($schedule->notes)
                                    <p class="text-xs text-gray-600">{{ Str::limit($schedule->notes, 50) }}</p>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tuk-schedules.edit', $schedule) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk-schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
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
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">event</span>
                                <p class="text-gray-500">No schedules found</p>
                                @if($search || $tukId || $status)
                                    <a href="{{ route('admin.tuk-schedules.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                                        Clear filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
@endsection
