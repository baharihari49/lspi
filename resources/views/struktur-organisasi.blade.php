@extends('layouts.app')

@section('title', 'Bagan Struktur Organisasi - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'struktur-organisasi';
@endphp

@section('extra_css')
    <style>
        .org-chart-node {
            position: relative;
        }

        .org-chart-node:not(:only-child):not(:first-child):before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: -1rem;
            top: 50%;
            height: 1px;
            width: 1rem;
        }

        .org-chart-node:not(:only-child):not(:last-child):after {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            right: -1rem;
            top: 50%;
            height: 1px;
            width: 1rem;
        }

        .org-chart-node.has-children:before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: 50%;
            bottom: -1rem;
            width: 1px;
            height: 1rem;
        }

        .org-chart-node:not(.root):after {
             content: '';
             position: absolute;
             background-color: #CFD6E7;
             left: 50%;
             top: -1rem;
             width: 1px;
             height: 1rem;
        }

        .org-chart-group:before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: 0;
            top: 0;
            width: 100%;
            height: 1px;
        }

        .org-chart-group > .org-chart-node:first-child:before, .org-chart-group > .org-chart-node:last-child:after {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12 md:py-20">
        <!-- Page Heading -->
        <div class="mb-12 text-center md:mb-16">
            <h1 class="text-4xl font-black tracking-tight text-blue-900 md:text-5xl">Bagan Struktur Organisasi</h1>
            <div class="mx-auto mt-2 h-1 w-24 bg-red-700"></div>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600">Tim profesional yang mendorong misi kami dan menjaga standar sertifikasi profesional.</p>
        </div>

        @if($positions->count() > 0)
            <!-- Organizational Chart -->
            <div class="flex flex-col items-center gap-y-8">
                @foreach($positions as $position)
                    <!-- Root Level Position -->
                    <div class="flex justify-center">
                        <div class="org-chart-node {{ $position->children->count() > 0 ? 'has-children' : '' }} rounded-lg border border-blue-900 bg-white p-4 shadow-md text-center min-w-[220px]">
                            @if($position->photo)
                                <div class="flex justify-center mb-2">
                                    <img src="{{ Storage::url($position->photo) }}" alt="{{ $position->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-blue-900">
                                </div>
                            @endif
                            <h2 class="text-base font-bold text-blue-900">{{ $position->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $position->position }}</p>
                            @if($position->email)
                                <p class="text-xs text-gray-400 mt-1">{{ $position->email }}</p>
                            @endif
                        </div>
                    </div>

                    @if($position->children->count() > 0)
                        <!-- Children Level -->
                        <div class="relative w-full overflow-x-auto px-4">
                            <div class="org-chart-group relative flex justify-center gap-x-8 py-4">
                                @foreach($position->children as $child)
                                    <div class="org-chart-node {{ $child->children->count() > 0 ? 'has-children' : '' }} shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                        @if($child->photo)
                                            <div class="flex justify-center mb-2">
                                                <img src="{{ Storage::url($child->photo) }}" alt="{{ $child->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300">
                                            </div>
                                        @endif
                                        <h2 class="text-sm font-bold">{{ $child->name }}</h2>
                                        <p class="text-xs text-gray-500">{{ $child->position }}</p>
                                        @if($child->email)
                                            <p class="text-xs text-gray-400 mt-1">{{ $child->email }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @php
                            // Get all grandchildren from all children
                            $grandchildren = $position->children->flatMap->children;
                        @endphp

                        @if($grandchildren->count() > 0)
                            <!-- Grandchildren Level -->
                            <div class="relative w-full overflow-x-auto px-4">
                                <div class="org-chart-group relative flex justify-center gap-x-8 py-4">
                                    @foreach($grandchildren as $grandchild)
                                        <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[180px]">
                                            @if($grandchild->photo)
                                                <div class="flex justify-center mb-2">
                                                    <img src="{{ Storage::url($grandchild->photo) }}" alt="{{ $grandchild->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                                                </div>
                                            @endif
                                            <h2 class="text-sm font-bold">{{ $grandchild->name }}</h2>
                                            <p class="text-xs text-gray-500">{{ $grandchild->position }}</p>
                                            @if($grandchild->email)
                                                <p class="text-xs text-gray-400 mt-1">{{ $grandchild->email }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">account_tree</span>
                <p class="text-gray-500 text-lg">Struktur organisasi belum tersedia</p>
            </div>
        @endif
    </div>
@endsection
