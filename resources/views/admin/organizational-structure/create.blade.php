@extends('layouts.admin')

@section('title', 'Tambah Posisi')

@php
    $active = 'organizational-structure';
@endphp

@section('page_title', 'Tambah Posisi Baru')
@section('page_description', 'Tambahkan posisi/jabatan baru dalam struktur organisasi')

@section('content')
    <form action="{{ route('admin.organizational-structure.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @include('admin.organizational-structure.form', ['position' => null, 'allPositions' => $allPositions])
    </form>
@endsection
