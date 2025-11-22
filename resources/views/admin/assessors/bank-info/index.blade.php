@extends('layouts.admin')
@section('title', 'Assessor Bank Information')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Assessor Bank Information</h1>
        <a href="{{ route('admin.assessor-bank-info.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Add Bank Info</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Bank information table - To be implemented</p>
        <div class="mt-4">{{ $bankInfos->links() }}</div>
    </div>
</div>
@endsection
