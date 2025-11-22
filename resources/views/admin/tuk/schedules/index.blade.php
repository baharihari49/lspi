@extends('layouts.admin')

@section('title', 'TUK Schedules')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">TUK Schedules</h1>
        <a href="{{ route('admin.tuk-schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800">
            <span class="material-symbols-outlined mr-2">add</span>
            Add Schedule
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.tuk-schedules.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Search schedules..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <select name="tuk_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All TUK</option>
                    @foreach($tuks as $tuk)
                        <option value="{{ $tuk->id }}" {{ ($tukId ?? '') == $tuk->id ? 'selected' : '' }}>
                            {{ $tuk->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="available" {{ ($status ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="booked" {{ ($status ?? '') == 'booked' ? 'selected' : '' }}>Booked</option>
                    <option value="blocked" {{ ($status ?? '') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    <option value="maintenance" {{ ($status ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800">
                    Search
                </button>
                @if($search || $tukId || $status)
                    <a href="{{ route('admin.tuk-schedules.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TUK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50 {{ $schedule->date->isPast() && $schedule->status === 'available' ? 'bg-gray-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $schedule->tuk->name }}</div>
                                <div class="text-xs text-gray-500">{{ $schedule->tuk->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $schedule->date->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $schedule->date->diffForHumans() }}
                                </div>
                                @if($schedule->date->isPast() && $schedule->status === 'available')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        Past
                                    </span>
                                @elseif($schedule->date->isToday())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Today
                                    </span>
                                @elseif($schedule->date->isTomorrow())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Tomorrow
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="material-symbols-outlined text-sm align-middle">schedule</span>
                                    {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->diffInHours(\Carbon\Carbon::parse($schedule->end_time)) }} hours
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($schedule->status === 'available') bg-green-100 text-green-800
                                    @elseif($schedule->status === 'booked') bg-blue-100 text-blue-800
                                    @elseif($schedule->status === 'blocked') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->status === 'available' || $schedule->status === 'booked')
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->available_capacity ?? 0 }} / {{ $schedule->tuk->capacity }}
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $schedule->tuk->capacity > 0 ? (($schedule->available_capacity ?? 0) / $schedule->tuk->capacity * 100) : 0 }}%"></div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($schedule->notes)
                                    <div class="text-xs text-gray-600">{{ Str::limit($schedule->notes, 50) }}</div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.tuk-schedules.edit', $schedule) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk-schedules.destroy', $schedule) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No schedules found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection
