@extends('layouts.admin')

@section('title', 'TUK Documents')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">TUK Documents</h1>
        <a href="{{ route('admin.tuk-documents.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800">
            <span class="material-symbols-outlined mr-2">add</span>
            Add Document
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.tuk-documents.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Search documents..."
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
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800">
                    Search
                </button>
                @if($search || $tukId)
                    <a href="{{ route('admin.tuk-documents.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $document->tuk->name }}</div>
                                <div class="text-xs text-gray-500">{{ $document->tuk->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-2">
                                    @if($document->file)
                                        <a href="{{ Storage::url($document->file->path) }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Download file">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                        @if($document->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($document->description, 50) }}</div>
                                        @endif
                                        @if($document->file)
                                            <div class="text-xs text-gray-400 mt-1">
                                                <span class="material-symbols-outlined text-xs align-middle">attach_file</span>
                                                {{ $document->file->filename }} ({{ number_format($document->file->size / 1024, 2) }} KB)
                                            </div>
                                        @endif
                                        @if($document->uploader)
                                            <div class="text-xs text-gray-400 mt-1">
                                                <span class="material-symbols-outlined text-xs align-middle">person</span>
                                                {{ $document->uploader->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $document->documentType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($document->issued_date)
                                    <span class="text-sm text-gray-900">{{ $document->issued_date->format('d M Y') }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($document->expiry_date)
                                    <div>
                                        <span class="text-sm text-gray-900">{{ $document->expiry_date->format('d M Y') }}</span>
                                        @if($document->expiry_date->isPast())
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                        @elseif($document->expiry_date->lte(now()->addDays(30)))
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Expiring Soon</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($document->status)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $document->status->label }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.tuk-documents.edit', $document) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tuk-documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
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
                                No documents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
