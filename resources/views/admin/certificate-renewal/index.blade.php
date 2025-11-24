@extends('layouts.admin')

@section('title', 'Certificate Renewals')

@php
    $active = 'certificate-renewal';
@endphp

@section('page_title', 'Certificate Renewals')
@section('page_description', 'Manage certificate renewal requests and processes')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.certificate-renewal.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search renewal number, certificate..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_assessment" {{ request('status') === 'in_assessment' ? 'selected' : '' }}>In Assessment</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="renewal_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Types</option>
                    <option value="standard" {{ request('renewal_type') === 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="simplified" {{ request('renewal_type') === 'simplified' ? 'selected' : '' }}>Simplified</option>
                    <option value="automatic" {{ request('renewal_type') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                    <option value="early_renewal" {{ request('renewal_type') === 'early_renewal' ? 'selected' : '' }}>Early Renewal</option>
                    <option value="late_renewal" {{ request('renewal_type') === 'late_renewal' ? 'selected' : '' }}>Late Renewal</option>
                    <option value="grace_period_renewal" {{ request('renewal_type') === 'grace_period_renewal' ? 'selected' : '' }}>Grace Period</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.certificate-renewal.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Renewals Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Renewal Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Request Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($renewals as $renewal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">autorenew</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $renewal->renewal_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">ID: #{{ $renewal->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $renewal->originalCertificate->certificate_number }}</p>
                                    <p class="text-sm text-gray-500">Expires: {{ $renewal->original_expiry_date->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $renewal->assessee->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                    {{ str_replace('_', ' ', ucfirst($renewal->renewal_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $renewal->renewal_request_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'in_assessment' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'In Assessment'],
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Approved'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Rejected'],
                                        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Cancelled'],
                                    ];
                                    $config = $statusConfig[$renewal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $renewal->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificate-renewal.show', $renewal) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>

                                    @if(in_array($renewal->status, ['pending', 'in_assessment']))
                                        <a href="{{ route('admin.certificate-renewal.edit', $renewal) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined text-xl">edit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-6xl text-gray-300">autorenew</span>
                                    <p class="text-gray-500">No renewal requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($renewals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $renewals->links() }}
            </div>
        @endif
    </div>
@endsection
