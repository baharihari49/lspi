@extends('layouts.admin')

@section('title', 'Event Attendance')

@php
    $active = 'events';
@endphp

@section('page_title', 'Event Attendance')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-900">Events</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-blue-900">{{ $event->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">Attendance</span>
        </nav>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
                <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
                <span class="material-symbols-outlined text-red-600 mr-3">error</span>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Attendance List</h2>
                        <p class="text-sm text-gray-600">Total: {{ $attendance->total() }} records</p>
                    </div>
                    <a href="{{ route('admin.events.attendance.create', $event) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Record Attendance</span>
                    </a>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.events.attendance.index', $event) }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <select name="session_id" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">All Sessions</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }} - {{ $session->session_date->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">All Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        </select>

                        <div class="flex gap-2">
                            <button type="submit" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                                Filter
                            </button>
                            <a href="{{ route('admin.events.attendance.index', $event) }}" class="h-10 px-4 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition flex items-center">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if($attendance->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Participant</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($attendance as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $record->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $record->user->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->session)
                                            <p class="text-sm text-gray-900">{{ $record->session->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $record->session->session_date->format('d M Y') }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->check_in_at)
                                            <p class="text-sm text-gray-900">{{ $record->check_in_at->format('H:i') }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($record->check_in_method) }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->check_out_at)
                                            <p class="text-sm text-gray-900">{{ $record->check_out_at->format('H:i') }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($record->check_out_method) }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'present' => 'bg-green-100 text-green-700',
                                                'absent' => 'bg-red-100 text-red-700',
                                                'excused' => 'bg-yellow-100 text-yellow-700',
                                                'late' => 'bg-orange-100 text-orange-700',
                                            ];
                                        @endphp
                                        <span class="inline-block px-3 py-1 {{ $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$record->check_in_at)
                                                <form action="{{ route('admin.events.attendance.check-in', [$event, $record]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Check In">
                                                        <span class="material-symbols-outlined text-lg">login</span>
                                                    </button>
                                                </form>
                                            @elseif(!$record->check_out_at)
                                                <form action="{{ route('admin.events.attendance.check-out', [$event, $record]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Check Out">
                                                        <span class="material-symbols-outlined text-lg">logout</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.events.attendance.edit', [$event, $record]) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.events.attendance.destroy', [$event, $record]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($attendance->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $attendance->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">how_to_reg</span>
                    <p class="text-gray-500">No attendance records found</p>
                </div>
            @endif
        </div>
    </div>
@endsection
