@extends('layouts.admin')
@section('title', 'Assessor Competency Scopes')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Assessor Competency Scopes</h1>
        <a href="{{ route('admin.assessor-competency-scopes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Add Competency</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Competency scopes table - To be implemented</p>
        <div class="mt-4">{{ $competencyScopes->links() }}</div>
    </div>
</div>
@endsection
