@extends('layouts.admin')

@section('title', 'TUK Details')

@php
    $active = 'tuk';
@endphp

@section('page_title', 'TUK Details')
@section('page_description', 'View Tempat Uji Kompetensi information')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">tag</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">TUK Code</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $tuk->code }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">category</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">TUK Type</p>
                                @php
                                    $typeColors = [
                                        'permanent' => 'bg-green-100 text-green-700',
                                        'temporary' => 'bg-yellow-100 text-yellow-700',
                                        'mobile' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $typeColors[$tuk->type] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($tuk->type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">location_city</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">TUK Name</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->name }}</p>
                            </div>
                        </div>
                    </div>
                    @if($tuk->status)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">verified</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $tuk->status->label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($tuk->manager)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Manager</p>
                                    <p class="font-semibold text-gray-900">{{ $tuk->manager->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($tuk->description)
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">description</span>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 mb-2">Description</p>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-700">{{ $tuk->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Location Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">home</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Address</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->address }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">location_city</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">City</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->city }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">map</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Province</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->province }}</p>
                            </div>
                        </div>
                    </div>
                    @if($tuk->postal_code)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">markunread_mailbox</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Postal Code</p>
                                    <p class="font-semibold text-gray-900 font-mono">{{ $tuk->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($tuk->latitude && $tuk->longitude)
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">pin_drop</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">GPS Coordinates</p>
                                    <p class="font-semibold text-gray-900 font-mono">{{ $tuk->latitude }}, {{ $tuk->longitude }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            @if($tuk->phone || $tuk->email || $tuk->pic_name || $tuk->pic_phone)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($tuk->phone)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">phone</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                                        <a href="tel:{{ $tuk->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $tuk->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($tuk->email)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">email</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <a href="mailto:{{ $tuk->email }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $tuk->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($tuk->pic_name)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">person</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">PIC Name</p>
                                        <p class="font-semibold text-gray-900">{{ $tuk->pic_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($tuk->pic_phone)
                            <div>
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 mt-0.5">phone</span>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">PIC Phone</p>
                                        <a href="tel:{{ $tuk->pic_phone }}" class="font-semibold text-blue-600 hover:text-blue-800">{{ $tuk->pic_phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Facility Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Facility Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">groups</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Capacity</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->capacity }} <span class="text-xs text-gray-500">people</span></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-400 mt-0.5">meeting_room</span>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Room Count</p>
                                <p class="font-semibold text-gray-900">{{ $tuk->room_count }} <span class="text-xs text-gray-500">rooms</span></p>
                            </div>
                        </div>
                    </div>
                    @if($tuk->area_size)
                        <div>
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-gray-400 mt-0.5">square_foot</span>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Area Size</p>
                                    <p class="font-semibold text-gray-900">{{ $tuk->area_size }} <span class="text-xs text-gray-500">m²</span></p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Facilities List -->
            @if($tuk->facilities->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Facilities & Equipment</h3>

                    <div class="space-y-3">
                        @foreach($tuk->facilities as $facility)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">inventory_2</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $facility->quantity }} • {{ ucfirst($facility->condition) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Documents List -->
            @if($tuk->documents->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Documents</h3>

                    <div class="space-y-3">
                        @foreach($tuk->documents as $document)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">description</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $document->document_type }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $document->file->filename ?? 'N/A' }}</p>
                                </div>
                                @if($document->file)
                                    <a href="{{ asset('storage/' . $document->file->path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        <span>Download</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Schedules List -->
            @if($tuk->schedules->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Schedules</h3>

                    <div class="space-y-3">
                        @foreach($tuk->schedules as $schedule)
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">calendar_month</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $schedule->title }}</p>
                                    @if($schedule->start_date && $schedule->end_date)
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $schedule->start_date->format('d M Y') }} - {{ $schedule->end_date->format('d M Y') }}
                                        </p>
                                    @endif
                                    @if($schedule->notes)
                                        <p class="text-xs text-gray-500 mt-2">{{ $schedule->notes }}</p>
                                    @endif
                                </div>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'ongoing' => 'bg-green-100 text-green-700',
                                        'completed' => 'bg-gray-100 text-gray-600',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Active Status</p>
                        @if($tuk->is_active)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                Active
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Facilities:</span>
                        <span class="font-semibold text-gray-900">{{ $tuk->facilities->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Documents:</span>
                        <span class="font-semibold text-gray-900">{{ $tuk->documents->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Schedules:</span>
                        <span class="font-semibold text-gray-900">{{ $tuk->schedules->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold text-gray-900">{{ $tuk->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="font-semibold text-gray-900">{{ $tuk->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.tuk.edit', $tuk) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">edit</span>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.tuk.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    <form action="{{ route('admin.tuk.destroy', $tuk) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this TUK?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">delete</span>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
