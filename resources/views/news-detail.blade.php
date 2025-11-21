@extends('layouts.app')

@section('title', $news->title . ' - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'news';
@endphp

@section('content')
    <main class="flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col w-full max-w-4xl flex-1 px-4">
            <!-- Back Button -->
            <div class="py-8">
                <a class="inline-flex items-center gap-2 text-blue-900 hover:underline" href="{{ url('/news') }}">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="text-sm font-medium">Kembali ke Berita & Artikel</span>
                </a>
            </div>

            <!-- Article Content -->
            <article class="flex flex-col gap-6">
                <!-- Article Header -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">{{ $news->category }}</span>
                        <p class="text-sm text-gray-600">{{ $news->published_at->format('d F Y') }}</p>
                    </div>
                    <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] text-gray-900">{{ $news->title }}</h1>
                    <p class="text-lg text-gray-600 mt-2">{{ $news->excerpt }}</p>
                </div>

                <!-- Featured Image -->
                @if($news->image)
                    <div class="w-full bg-center bg-no-repeat aspect-[2/1] bg-cover rounded-lg" style="background-image: url('{{ $news->image }}');"></div>
                @else
                    <div class="w-full aspect-[2/1] bg-gradient-to-br from-{{ $news->icon_color }}-500 to-{{ $news->icon_color }}-700 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-8xl">{{ $news->icon }}</span>
                    </div>
                @endif

                <!-- Article Body -->
                <div class="prose prose-lg max-w-none text-base text-gray-600 leading-relaxed">
                    {!! $news->content !!}
                </div>
            </article>

            <!-- Recent Articles Section -->
            @if($recentNews->count() > 0)
                <section class="py-12 border-t border-gray-200 mt-12">
                    <h2 class="text-3xl font-bold text-blue-900 mb-8">Artikel Terbaru Lainnya</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                        @foreach($recentNews as $item)
                            <div class="flex flex-col gap-4">
                                <a class="block" href="{{ url('/news/' . $item->slug) }}">
                                    @if($item->image)
                                        <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg hover:opacity-90 transition" style="background-image: url('{{ $item->image }}');"></div>
                                    @else
                                        <div class="w-full aspect-video bg-gradient-to-br from-{{ $item->icon_color }}-500 to-{{ $item->icon_color }}-700 rounded-lg flex items-center justify-center hover:opacity-90 transition">
                                            <span class="material-symbols-outlined text-white text-5xl">{{ $item->icon }}</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex flex-col gap-1">
                                    <p class="text-xs text-gray-500">{{ $item->published_at->format('d F Y') }}</p>
                                    <a class="hover:underline" href="{{ url('/news/' . $item->slug) }}">
                                        <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $item->title }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $item->excerpt }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 text-center">
                        <a class="inline-flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-6 bg-red-700 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-800 transition" href="{{ url('/news') }}">
                            <span class="truncate">Lihat Semua Artikel</span>
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </main>

    <style>
        /* Trix Editor Output Styling */
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1e3a8a;
            font-weight: bold;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }

        .prose h1 { font-size: 2em; }
        .prose h2 { font-size: 1.5em; }
        .prose h3 { font-size: 1.25em; }

        .prose p {
            color: #4b5563;
            margin-bottom: 1em;
            line-height: 1.75;
        }

        .prose ul, .prose ol {
            color: #4b5563;
            margin: 1em 0;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose strong {
            color: #1f2937;
            font-weight: 600;
        }

        .prose a {
            color: #b91c1c;
            text-decoration: underline;
        }

        .prose a:hover {
            color: #991b1b;
        }

        .prose blockquote {
            border-left: 4px solid #1e3a8a;
            padding-left: 1em;
            margin: 1.5em 0;
            font-style: italic;
            color: #6b7280;
        }
    </style>
@endsection
