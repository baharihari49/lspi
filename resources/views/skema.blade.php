@extends('layouts.app')

@section('title', 'Skema Sertifikasi - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'skema';
@endphp

@section('content')
    <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-12 md:py-16">
        <div class="flex flex-col max-w-[1200px] w-full">
            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-2">Skema Sertifikasi</h1>
                <p class="text-lg text-gray-600">Temukan skema sertifikasi yang sesuai dengan bidang keahlian Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Card 1 -->
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col overflow-hidden border border-gray-200">
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-900 text-3xl">article</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight">Penerapan IT Untuk Artikel Ilmiah</h3>
                                <p class="text-sm text-gray-500 mt-1">Application IT for Scientific Article</p>
                            </div>
                        </div>
                        <p class="text-base text-gray-600 mb-4 flex-grow">Bidang Perpustakaan (Library)</p>
                        <span class="inline-block bg-blue-100 text-blue-900 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider self-start">KKNI (LEVEL 3)</span>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <a class="bg-red-700 hover:bg-red-800 text-white font-bold py-2.5 px-4 rounded-lg w-full text-center inline-block transition duration-300" href="/skema/penerapan-it-artikel-ilmiah">Lihat Detail</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col overflow-hidden border border-gray-200">
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-900 text-3xl">menu_book</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight">Pengelolaan Jurnal Elektronik</h3>
                                <p class="text-sm text-gray-500 mt-1">Electronic Journal Management</p>
                            </div>
                        </div>
                        <p class="text-base text-gray-600 mb-4 flex-grow">Bidang Perpustakaan (Library)</p>
                        <span class="inline-block bg-blue-100 text-blue-900 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider self-start">KKNI (LEVEL 3)</span>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <a class="bg-red-700 hover:bg-red-800 text-white font-bold py-2.5 px-4 rounded-lg w-full text-center inline-block transition duration-300" href="/skema/pengelolaan-jurnal-elektronik">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
