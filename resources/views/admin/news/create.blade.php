@extends('layouts.admin')

@section('title', 'Tambah Berita')

@php
    $active = 'news';
@endphp

@section('page_title', 'Tambah Berita Baru')
@section('page_description', 'Buat berita atau artikel baru')

@section('content')
    <form action="{{ route('admin.news.store') }}" method="POST" class="max-w-4xl">
        @csrf
        @include('admin.news.form', ['news' => null])
    </form>
@endsection
