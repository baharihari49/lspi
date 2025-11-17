@extends('layouts.admin')

@section('title', 'Edit Berita')

@php
    $active = 'news';
@endphp

@section('page_title', 'Edit Berita')
@section('page_description', 'Perbarui informasi berita')

@section('content')
    <form action="{{ route('admin.news.update', $news) }}" method="POST" class="max-w-4xl">
        @csrf
        @method('PUT')
        @include('admin.news.form', ['news' => $news])
    </form>
@endsection
