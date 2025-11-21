@extends('layouts.admin')

@section('title', 'Edit Berita')

@php
    $active = 'news';
@endphp

@section('page_title', 'Edit Berita')
@section('page_description', 'Perbarui informasi berita')

@section('content')
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')
        @include('admin.news.form', ['news' => $news])
    </form>
@endsection
