@extends('layouts.admin')

@section('title', 'Assessor Competency Scopes')

@php
    $active = 'assessor-competency-scopes';
@endphp

@section('page_title', 'Assessor Competency Scopes')
@section('page_description', 'Manage assessor competency certifications per scheme')

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
                    <h2 class="text-lg font-bold text-gray-900">Competency Scopes List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $competencyScopes->total() }} competency scopes</p>
                </div>
                <a href="{{ route('admin.assessor-competency-scopes.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Competency</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.assessor-competency-scopes.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by scheme, competency unit, certificate, or assessor..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request('search') || request('assessor_id') || request('approval_status') || request('expired'))
                        <a href="{{ route('admin.assessor-competency-scopes.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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

                    <select name="approval_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Approval Status</option>
                        <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="expired" {{ request('approval_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>

                    <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="expired" value="1" {{ request('expired') ? 'checked' : '' }} class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700">Show Expired Only</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Competency Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Certificate</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($competencyScopes as $scope)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($scope->assessor->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $scope->assessor->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $scope->assessor->registration_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $scope->scheme_name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $scope->scheme_code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $scope->competency_unit_title }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $scope->competency_unit_code }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($scope->certificate_number)
                                    <p class="text-sm font-mono text-gray-900">{{ $scope->certificate_number }}</p>
                                    @if($scope->certificate_expiry_date)
                                        <p class="text-xs text-gray-500 mt-1">Exp: {{ $scope->certificate_expiry_date->format('d M Y') }}</p>
                                        @if($scope->certificate_expiry_date->isPast())
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                                Expired
                                            </span>
                                        @elseif($scope->certificate_expiry_date->lte(now()->addDays(30)))
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                                Expiring Soon
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $approvalColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'expired' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $approvalColors[$scope->approval_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($scope->approval_status) }}
                                </span>
                                @if(!$scope->is_active)
                                    <span class="block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessor-competency-scopes.show', $scope) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessor-competency-scopes.edit', $scope) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.assessor-competency-scopes.destroy', $scope) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this competency scope?')">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">workspace_premium</span>
                                <p class="text-gray-500">No competency scopes found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($competencyScopes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $competencyScopes->links() }}
            </div>
        @endif
    </div>
@endsection
