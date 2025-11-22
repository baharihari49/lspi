@extends('layouts.admin')

@section('title', 'Event Materials')

@php
    $active = 'events';
@endphp

@section('page_title', 'Event Materials')
@section('page_description', $event->code . ' - ' . $event->name)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-900">Events</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-blue-900">{{ $event->code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">Materials</span>
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
                        <h2 class="text-lg font-bold text-gray-900">Materials List</h2>
                        <p class="text-sm text-gray-600">Total: {{ $materials->total() }} materials</p>
                    </div>
                    <a href="{{ route('admin.events.materials.create', $event) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">add</span>
                        <span>Upload Material</span>
                    </a>
                </div>
            </div>

            @if($materials->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Info</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Downloads</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($materials as $material)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-gray-400 text-2xl">description</span>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $material->title }}</p>
                                                @if($material->description)
                                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($material->description, 80) }}</p>
                                                @endif
                                                <div class="flex items-center gap-2 mt-1">
                                                    @if($material->is_public)
                                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">Public</span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-semibold">Private</span>
                                                    @endif
                                                    @if($material->is_downloadable)
                                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Downloadable</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $typeColors = [
                                                'presentation' => 'bg-purple-100 text-purple-700',
                                                'handout' => 'bg-blue-100 text-blue-700',
                                                'video' => 'bg-red-100 text-red-700',
                                                'document' => 'bg-green-100 text-green-700',
                                                'other' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span class="inline-block px-3 py-1 {{ $typeColors[$material->material_type] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                            {{ ucfirst($material->material_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($material->session)
                                            <p class="text-sm text-gray-900">{{ $material->session->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $material->session->session_date->format('d M Y') }}</p>
                                        @else
                                            <span class="text-sm text-gray-400">All sessions</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900">{{ $material->file_name }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($material->file_size / 1024, 2) }} KB</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900">{{ $material->download_count ?? 0 }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($material->is_downloadable)
                                                <a href="{{ route('admin.events.materials.download', [$event, $material]) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Download">
                                                    <span class="material-symbols-outlined text-lg">download</span>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.events.materials.edit', [$event, $material]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.events.materials.destroy', [$event, $material]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?')">
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

                @if($materials->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $materials->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">folder</span>
                    <p class="text-gray-500">No materials uploaded yet</p>
                </div>
            @endif
        </div>
    </div>
@endsection
