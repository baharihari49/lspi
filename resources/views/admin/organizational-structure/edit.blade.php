@extends('layouts.admin')

@section('title', 'Edit Posisi')

@php
    $active = 'organizational-structure';
@endphp

@section('page_title', 'Edit Posisi')
@section('page_description', 'Perbarui informasi posisi dalam struktur organisasi')

@section('content')
    <form action="{{ route('admin.organizational-structure.update', $organizationalStructure) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        @include('admin.organizational-structure.form', ['position' => $organizationalStructure, 'allPositions' => $allPositions])
    </form>
@endsection
