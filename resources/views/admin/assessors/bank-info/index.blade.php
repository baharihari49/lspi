@extends('layouts.admin')

@section('title', 'Assessor Bank Information')

@php
    $active = 'assessor-bank-info';
@endphp

@section('page_title', 'Assessor Bank Information')
@section('page_description', 'Manage assessor banking and tax information')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Bank Information List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $bankInfos->total() }} bank accounts</p>
                </div>
                <a href="{{ route('admin.assessor-bank-info.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Bank Info</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.assessor-bank-info.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by bank name, account number, holder name, NPWP, or assessor..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request('search') || request('assessor_id') || request('verification_status') || request('primary_only') || request('active_only'))
                        <a href="{{ route('admin.assessor-bank-info.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="flex gap-2">
                    <select name="assessor_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Assessors</option>
                        @foreach($assessors as $assessor)
                            <option value="{{ $assessor->id }}" {{ request('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                {{ $assessor->full_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="verification_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Verification Status</option>
                        <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="primary_only" value="1" {{ request('primary_only') ? 'checked' : '' }} class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700">Primary Only</span>
                    </label>

                    <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="active_only" value="1" {{ request('active_only') ? 'checked' : '' }} class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700">Active Only</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bank Account</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NPWP</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bankInfos as $bankInfo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($bankInfo->assessor->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $bankInfo->assessor->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $bankInfo->assessor->registration_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $bankInfo->bank_name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $bankInfo->account_number }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $bankInfo->account_holder_name }}</p>
                                @if($bankInfo->branch_name)
                                    <p class="text-xs text-gray-500">{{ $bankInfo->branch_name }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($bankInfo->npwp_number)
                                    <p class="text-sm font-mono text-gray-900">{{ $bankInfo->npwp_number }}</p>
                                    @if($bankInfo->tax_name)
                                        <p class="text-xs text-gray-500 mt-1">{{ $bankInfo->tax_name }}</p>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">Not provided</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $verificationColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $verificationColors[$bankInfo->verification_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($bankInfo->verification_status) }}
                                </span>
                                @if($bankInfo->is_primary)
                                    <span class="block mt-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                        Primary
                                    </span>
                                @endif
                                @if(!$bankInfo->is_active)
                                    <span class="block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessor-bank-info.show', $bankInfo) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessor-bank-info.edit', $bankInfo) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.assessor-bank-info.destroy', $bankInfo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this bank information?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">account_balance</span>
                                <p class="text-gray-500">No bank information found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bankInfos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bankInfos->links() }}
            </div>
        @endif
    </div>
@endsection
