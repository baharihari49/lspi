@extends('layouts.admin')

@section('title', 'Event Details')

@php
    $active = 'events';
@endphp

@section('page_title', $event->name)
@section('page_description', $event->code)

@section('content')
    <div class="w-full space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-900">Events</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-gray-900 font-semibold">{{ $event->code }}</span>
        </nav>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Event Information</h3>
                        <a href="{{ route('admin.events.edit', $event) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>

                    <!-- Status Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @php
                            $typeColors = [
                                'certification' => 'bg-blue-100 text-blue-700',
                                'training' => 'bg-green-100 text-green-700',
                                'workshop' => 'bg-purple-100 text-purple-700',
                                'other' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 {{ $typeColors[$event->event_type] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold uppercase">
                            {{ $event->event_type }}
                        </span>
                        @if($event->is_published)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                Published
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                Draft
                            </span>
                        @endif
                        @if($event->is_active)
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                Active
                            </span>
                        @endif
                        @if($event->status)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                {{ $event->status->label }}
                            </span>
                        @endif
                    </div>

                    @if($event->description)
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $event->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Event Dates</p>
                            <p class="text-sm text-gray-900">{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</p>
                        </div>
                        @if($event->registration_start && $event->registration_end)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Registration Period</p>
                                <p class="text-sm text-gray-900">{{ $event->registration_start->format('d M Y') }} - {{ $event->registration_end->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if($event->location)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Location</p>
                                <p class="text-sm text-gray-900">{{ $event->location }}</p>
                            </div>
                        @endif
                        @if($event->max_participants)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Capacity</p>
                                <p class="text-sm text-gray-900">{{ $event->current_participants }} / {{ $event->max_participants }} participants</p>
                            </div>
                        @endif
                        @if($event->registration_fee)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Registration Fee</p>
                                <p class="text-sm text-gray-900">Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</p>
                            </div>
                        @endif
                        @if($event->scheme)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Certification Scheme</p>
                                <p class="text-sm text-gray-900">{{ $event->scheme->code }} - {{ $event->scheme->name }}</p>
                            </div>
                        @endif
                    </div>

                    @if($event->location_address)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Location Address</p>
                            <p class="text-sm text-gray-700">{{ $event->location_address }}</p>
                        </div>
                    @endif

                    @if($event->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Internal Notes</p>
                            <p class="text-sm text-gray-700">{{ $event->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sessions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Event Sessions</h3>
                        <button class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Add Session</span>
                        </button>
                    </div>

                    @if($event->sessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($event->sessions as $session)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $session->name }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $session->session_date->format('d M Y') }} - {{ $session->session_type }}</p>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">event_note</span>
                            <p class="text-gray-500">No sessions added yet</p>
                        </div>
                    @endif
                </div>

                <!-- TUK Assignments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">TUK Assignments</h3>
                        <button class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Assign TUK</span>
                        </button>
                    </div>

                    @if($event->tuks->count() > 0)
                        <div class="space-y-2">
                            @foreach($event->tuks as $tukAssignment)
                                <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $tukAssignment->tuk->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $tukAssignment->tuk->code ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">location_city</span>
                            <p class="text-gray-500">No TUK assigned yet</p>
                        </div>
                    @endif
                </div>

                <!-- Assessors -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Assessors</h3>
                        <button class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Assign Assessor</span>
                        </button>
                    </div>

                    @if($event->assessors->count() > 0)
                        <div class="space-y-2">
                            @foreach($event->assessors as $assessor)
                                <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $assessor->assessor->name ?? 'N/A' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            @php
                                                $statusColors = [
                                                    'invited' => 'bg-gray-100 text-gray-700',
                                                    'confirmed' => 'bg-green-100 text-green-700',
                                                    'rejected' => 'bg-red-100 text-red-700',
                                                    'completed' => 'bg-blue-100 text-blue-700',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 {{ $statusColors[$assessor->status] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs font-semibold">
                                                {{ ucfirst($assessor->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">badge</span>
                            <p class="text-gray-500">No assessors assigned yet</p>
                        </div>
                    @endif
                </div>

                <!-- Materials -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Event Materials</h3>
                        <button class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Upload Material</span>
                        </button>
                    </div>

                    @if($event->materials->count() > 0)
                        <div class="space-y-2">
                            @foreach($event->materials as $material)
                                <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $material->title }}</p>
                                        <p class="text-xs text-gray-600">{{ ucfirst($material->material_type) }}</p>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Download">
                                            <span class="material-symbols-outlined">download</span>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">folder</span>
                            <p class="text-gray-500">No materials uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions & Stats -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Participants</span>
                                <span class="font-bold text-gray-900">{{ $event->current_participants }} / {{ $event->max_participants ?? '∞' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Sessions</span>
                                <span class="font-bold text-gray-900">{{ $event->sessions->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">TUK Assigned</span>
                                <span class="font-bold text-gray-900">{{ $event->tuks->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Assessors</span>
                                <span class="font-bold text-gray-900">{{ $event->assessors->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Materials</span>
                                <span class="font-bold text-gray-900">{{ $event->materials->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Duration</span>
                                <span class="font-bold text-gray-900">{{ $event->start_date->diffInDays($event->end_date) + 1 }} Days</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                        <div class="space-y-2">
                            @if(!$event->is_published)
                                <form action="{{ route('admin.events.publish', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition text-sm">
                                        <span class="material-symbols-outlined text-lg">public</span>
                                        <span>Publish Event</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.events.unpublish', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition text-sm">
                                        <span class="material-symbols-outlined text-lg">visibility_off</span>
                                        <span>Unpublish Event</span>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.events.edit', $event) }}" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-lg">edit</span>
                                <span>Edit Event</span>
                            </a>

                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Delete this event? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition text-sm">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                    <span>Delete Event</span>
                                </button>
                            </form>

                            <a href="{{ route('admin.events.index') }}" class="w-full flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-lg">arrow_back</span>
                                <span>Back to Events</span>
                            </a>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Created At</p>
                                <p class="text-gray-900">{{ $event->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                                <p class="text-gray-900">{{ $event->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                            @if($event->creator)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Created By</p>
                                    <p class="text-gray-900">{{ $event->creator->name }}</p>
                                </div>
                            @endif
                            @if($event->updater)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Updated By</p>
                                    <p class="text-gray-900">{{ $event->updater->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
