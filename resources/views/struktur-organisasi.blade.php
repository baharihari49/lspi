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

        <!-- Organizational Chart -->
        <div class="flex flex-col items-center gap-y-8">
            <!-- Level 1: Dewan Pengarah -->
            <div class="flex justify-center">
                <div class="org-chart-node has-children rounded-lg border border-blue-900 bg-white p-4 shadow-md text-center min-w-[220px]">
                    <h2 class="text-base font-bold text-blue-900">DEWAN PENGARAH</h2>
                    <p class="text-sm text-gray-500">Steering Committee</p>
                </div>
            </div>

            <!-- Level 2: Direktur Utama -->
            <div class="flex justify-center">
                <div class="org-chart-node has-children rounded-lg border border-blue-900 bg-white p-4 shadow-md text-center min-w-[220px]">
                    <h2 class="text-base font-bold text-blue-900">Dwi Fajar Saputra</h2>
                    <p class="text-sm text-gray-500">Direktur Utama</p>
                </div>
            </div>

            <!-- Level 3: Directors -->
            <div class="relative w-full overflow-x-auto px-4">
                <div class="org-chart-group relative flex justify-center gap-x-8 py-4">
                    <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                        <h2 class="text-sm font-bold">Muh Ilham Bakhtiar</h2>
                        <p class="text-xs text-gray-500">Komite Skema</p>
                    </div>
                    <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                        <h2 class="text-sm font-bold">Jamiludin Usman</h2>
                        <p class="text-xs text-gray-500">Direktur Manajemen Mutu</p>
                    </div>
                    <div class="org-chart-node has-children shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                        <h2 class="text-sm font-bold">Amardyasta G. Pratama</h2>
                        <p class="text-xs text-gray-500">Direktur Administrasi</p>
                    </div>
                    <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                        <h2 class="text-sm font-bold">Furaida Khasanah</h2>
                        <p class="text-xs text-gray-500">Direktur Keuangan</p>
                    </div>
                    <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                        <h2 class="text-sm font-bold">Zulidyana D. Rusnalasari</h2>
                        <p class="text-xs text-gray-500">Direktur Sertifikasi</p>
                    </div>
                </div>
            </div>

            <!-- Level 4: Manajer Representatif -->
            <div class="flex justify-center">
                <div class="org-chart-node rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[220px]">
                    <h2 class="text-sm font-bold">Yusuf Saefudin</h2>
                    <p class="text-xs text-gray-500">MANAJER REPRESENTATIF</p>
                </div>
            </div>
        </div>
    </div>
@endsection
