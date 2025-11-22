@extends('layouts.admin')

@section('title', 'TUK Documents Management')

@php
    $active = 'tuk-documents';
@endphp

@section('page_title', 'TUK Documents Management')
@section('page_description', 'Manage documents for Tempat Uji Kompetensi')

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
                    <h2 class="text-lg font-bold text-gray-900">Documents List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $documents->total() }} documents</p>
                </div>
                <a href="{{ route('admin.tuk-documents.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Document</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.tuk-documents.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by document title or description..."
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
                        <a href="{{ route('admin.tuk-documents.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Issued Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $document->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $document->tuk->code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    @if($document->file)
                                        <a href="{{ Storage::url($document->file->path) }}" target="_blank"
                                           class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition"
                                           title="Download file">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $document->title }}</p>
                                        @if($document->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($document->description, 60) }}</p>
                                        @endif
                                        @if($document->file)
                                            <p class="text-xs text-gray-400 mt-1">
                                                <span class="material-symbols-outlined text-xs align-middle">attach_file</span>
                                                {{ $document->file->filename }} ({{ number_format($document->file->size / 1024, 2) }} KB)
                                            </p>
                                        @endif
                                        @if($document->uploader)
                                            <p class="text-xs text-gray-400 mt-1">
                                                <span class="material-symbols-outlined text-xs align-middle">person</span>
                                                Uploaded by {{ $document->uploader->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ $document->documentType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($document->issued_date)
                                    <p class="text-sm font-semibold text-gray-900">{{ $document->issued_date->format('d M Y') }}</p>
                                @else
                                    <span class="text-sm text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($document->expiry_date)
                                    <p class="text-sm font-semibold text-gray-900">{{ $document->expiry_date->format('d M Y') }}</p>
                                    @if($document->expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Expired</span>
                                    @elseif($document->expiry_date->lte(now()->addDays(30)))
                                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Expiring Soon</span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">No expiry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($document->status)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        {{ $document->status->label }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tuk-documents.edit', $document) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk-documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?')">
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
                                <p class="text-gray-500">No documents found</p>
                                @if($search || $tukId)
                                    <a href="{{ route('admin.tuk-documents.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-semibold">
                                        Clear filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@endsection
