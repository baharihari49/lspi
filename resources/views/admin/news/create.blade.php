@extends('layouts.admin')

@section('title', 'Tambah Berita')

@php
    $active = 'news';
@endphp

@section('page_title', 'Tambah Berita Baru')
@section('page_description', 'Buat berita atau artikel baru')

@section('content')
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @include('admin.news.form', ['news' => null])
    </form>
@endsection
