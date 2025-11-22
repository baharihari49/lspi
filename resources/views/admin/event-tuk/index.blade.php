@extends('layouts.admin')

@section('title', 'TUK Assignments')

@php
    $active = 'events';
@endphp

@section('page_title', 'TUK Assignments')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-900">Events</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-blue-900">{{ $event->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">TUK Assignments</span>
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
                        <h2 class="text-lg font-bold text-gray-900">TUK Assignments List</h2>
                        <p class="text-sm text-gray-600">Total: {{ $tuks->total() }} TUK assignments</p>
                    </div>
                    <a href="{{ route('admin.events.tuk.create', $event) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Assign TUK</span>
                    </a>
                </div>
            </div>

            @if($tuks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUK</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tuks as $tukAssignment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $tukAssignment->tuk->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $tukAssignment->tuk->code ?? 'N/A' }}</p>
                                        @if($tukAssignment->is_primary)
                                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Primary</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($tukAssignment->session)
                                            <p class="text-sm text-gray-900">{{ $tukAssignment->session->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $tukAssignment->session->session_date->format('d M Y') }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">All sessions</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'confirmed' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="inline-block px-3 py-1 {{ $statusColors[$tukAssignment->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                            {{ ucfirst($tukAssignment->status) }}
                                        </span>
                                        @if($tukAssignment->status === 'confirmed' && $tukAssignment->confirmed_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $tukAssignment->confirmed_at->format('d M Y') }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($tukAssignment->notes)
                                            <p class="text-sm text-gray-900">{{ Str::limit($tukAssignment->notes, 50) }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($tukAssignment->status === 'pending')
                                                <form action="{{ route('admin.events.tuk.confirm', [$event, $tukAssignment]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Confirm">
                                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.events.tuk.edit', [$event, $tukAssignment]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.events.tuk.destroy', [$event, $tukAssignment]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this TUK assignment?')">
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

                @if($tuks->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $tuks->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">location_city</span>
                    <p class="text-gray-500">No TUK assigned yet</p>
                </div>
            @endif
        </div>
    </div>
@endsection
