@extends('layouts.admin')

@section('title', 'Event Sessions')

@php
    $active = 'events';
@endphp

@section('page_title', 'Event Sessions')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-900">Events</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-blue-900">{{ $event->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">Sessions</span>
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
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Sessions List</h2>
                        <p class="text-sm text-gray-600">Total: {{ $sessions->total() }} sessions</p>
                    </div>
                    <a href="{{ route('admin.events.sessions.create', $event) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Add Session</span>
                    </a>
                </div>
            </div>

            @if($sessions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sessions as $session)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $session->name }}</p>
                                        @if($session->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($session->description, 50) }}</p>
                                        @endif
                                        @if(!$session->is_active)
                                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-700',
                                                'ongoing' => 'bg-yellow-100 text-yellow-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="inline-block px-3 py-1 {{ $statusColors[$session->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                            {{ ucfirst($session->status ?? 'scheduled') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900">{{ $session->session_date->format('d M Y') }}</p>
                                        @if($session->start_time && $session->end_time)
                                            <p class="text-xs text-gray-500">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($session->room)
                                            <p class="text-sm text-gray-900">{{ $session->room }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900">{{ $session->max_participants ?? 'Unlimited' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.events.sessions.edit', [$event, $session]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.events.sessions.destroy', [$event, $session]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')">
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

                @if($sessions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $sessions->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">event_note</span>
                    <p class="text-gray-500">No sessions found</p>
                </div>
            @endif
        </div>
    </div>
@endsection
