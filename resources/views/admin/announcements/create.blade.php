@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')

@php
    $active = 'announcements';
@endphp

@section('page_title', 'Tambah Pengumuman Baru')
@section('page_description', 'Buat pengumuman resmi baru')

@section('content')
    <form action="{{ route('admin.announcements.store') }}" method="POST" class="max-w-4xl">
        @csrf
        @include('admin.announcements.form', ['announcement' => null])
    </form>
@endsection
