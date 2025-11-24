@extends('layouts.admin')

@section('title', 'Manage Certificates')

@php
    $active = 'certificates';
@endphp

@section('page_title', 'Certificates')
@section('page_description', 'Manage issued certificates and their lifecycle')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search certificate number, assessee name..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="renewed" {{ request('status') === 'renewed' ? 'selected' : '' }}>Renewed</option>
                </select>

                <select name="scheme_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Schemes</option>
                    @foreach($schemes as $scheme)
                        <option value="{{ $scheme->id }}" {{ request('scheme_id') == $scheme->id ? 'selected' : '' }}>
                            {{ $scheme->code }}
                        </option>
                    @endforeach
                </select>

                <select name="expiry" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Expiry Status</option>
                    <option value="expiring_soon" {{ request('expiry') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                    <option value="expired" {{ request('expiry') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">schedule</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Expiring Soon</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['expiring_soon'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-gray-100 rounded-lg">
                    <span class="material-symbols-outlined text-gray-600">event_busy</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Expired</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['expired'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">block</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Revoked</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['revoked'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">autorenew</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Renewed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['renewed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Issue Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Valid Until</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($certificates as $certificate)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">workspace_premium</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $certificate->certificate_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $certificate->holder_name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $certificate->registration_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $certificate->assessee->name ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">{{ $certificate->assessee->assessee_number ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $certificate->scheme->code }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($certificate->scheme->name, 30) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $certificate->issue_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $certificate->valid_until->format('d M Y') }}</p>
                                    @if($certificate->isExpiringSoon())
                                        <p class="text-xs text-yellow-600 font-medium">{{ $certificate->daysUntilExpiry() }} days left</p>
                                    @elseif($certificate->isExpired())
                                        <p class="text-xs text-red-600 font-medium">Expired</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Active'],
                                        'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Expired'],
                                        'revoked' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Revoked'],
                                        'suspended' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Suspended'],
                                        'renewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Renewed'],
                                    ];
                                    $config = $statusConfig[$certificate->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificate->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificates.show', $certificate) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>

                                    @if($certificate->status !== 'revoked')
                                        <a href="{{ route('admin.certificates.edit', $certificate) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined text-xl">edit</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.certificates.download', $certificate) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Download">
                                        <span class="material-symbols-outlined text-xl">download</span>
                                    </a>

                                    @if($certificate->status === 'active' || $certificate->status === 'expired')
                                        <a href="{{ route('admin.certificate-renewal.create', ['certificate_id' => $certificate->id]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Renew">
                                            <span class="material-symbols-outlined text-xl">autorenew</span>
                                        </a>
                                    @endif

                                    @if($certificate->status !== 'revoked')
                                        <a href="{{ route('admin.certificate-revoke.create', ['certificate_id' => $certificate->id]) }}" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Revoke">
                                            <span class="material-symbols-outlined text-xl">block</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-6xl text-gray-300">workspace_premium</span>
                                    <p class="text-gray-500">No certificates found</p>
                                    <a href="{{ route('admin.certificates.create') }}" class="mt-2 text-blue-600 hover:text-blue-700 font-medium">
                                        Issue your first certificate
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
