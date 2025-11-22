@extends('layouts.admin')

@section('title', 'Assessor Experiences')

@php
    $active = 'assessor-experiences';
@endphp

@section('page_title', 'Assessor Experiences')
@section('page_description', 'Manage assessor work experience and professional background')

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
                    <h2 class="text-lg font-bold text-gray-900">Experiences List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $experiences->total() }} experiences</p>
                </div>
                <a href="{{ route('admin.assessor-experiences.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Experience</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.assessor-experiences.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by organization, position, location, or assessor..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request('search') || request('assessor_id') || request('experience_type') || request('current_only'))
                        <a href="{{ route('admin.assessor-experiences.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="flex gap-2">
                    <select name="assessor_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Assessors</option>
                        @foreach($assessors as $assessor)
                            <option value="{{ $assessor->id }}" {{ request('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                {{ $assessor->full_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="experience_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Experience Types</option>
                        <option value="assessment" {{ request('experience_type') === 'assessment' ? 'selected' : '' }}>Assessment</option>
                        <option value="training" {{ request('experience_type') === 'training' ? 'selected' : '' }}>Training</option>
                        <option value="industry" {{ request('experience_type') === 'industry' ? 'selected' : '' }}>Industry</option>
                        <option value="other" {{ request('experience_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>

                    <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="current_only" value="1" {{ request('current_only') ? 'checked' : '' }} class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700">Current Positions Only</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($experiences as $experience)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($experience->assessor->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $experience->assessor->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $experience->assessor->registration_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $experience->position }}</p>
                                @if($experience->location)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <span class="material-symbols-outlined text-xs align-middle">location_on</span>
                                        {{ $experience->location }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $experience->organization_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $experience->start_date->format('M Y') }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($experience->is_current)
                                        to <span class="font-semibold text-green-700">Present</span>
                                    @elseif($experience->end_date)
                                        to {{ $experience->end_date->format('M Y') }}
                                    @endif
                                </p>
                                @if($experience->duration)
                                    <p class="text-xs text-gray-500 mt-1">
                                        ({{ $experience->duration }} months)
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $typeColors = [
                                        'assessment' => 'bg-blue-100 text-blue-700',
                                        'training' => 'bg-purple-100 text-purple-700',
                                        'industry' => 'bg-green-100 text-green-700',
                                        'other' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $typeColors[$experience->experience_type] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($experience->experience_type) }}
                                </span>
                                @if($experience->is_current)
                                    <span class="block mt-1 px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                        Current
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessor-experiences.show', $experience) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessor-experiences.edit', $experience) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.assessor-experiences.destroy', $experience) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this experience?')">
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
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">work_history</span>
                                <p class="text-gray-500">No experiences found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($experiences->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $experiences->links() }}
            </div>
        @endif
    </div>
@endsection
