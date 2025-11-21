@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@php
    $active = 'announcements';
@endphp

@section('page_title', 'Edit Pengumuman')
@section('page_description', 'Perbarui informasi pengumuman')

@section('content')
    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')
        @include('admin.announcements.form', ['announcement' => $announcement])
    </form>
@endsection
