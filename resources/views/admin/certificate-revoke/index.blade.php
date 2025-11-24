@extends('layouts.admin')

@section('title', 'Certificate Revocations')

@php
    $active = 'certificate-revoke';
@endphp

@section('page_title', 'Certificate Revocations')
@section('page_description', 'Manage certificate revocation requests and processes')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.certificate-revoke.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search revocation number, certificate..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="appealed" {{ request('status') === 'appealed' ? 'selected' : '' }}>Appealed</option>
                    <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                </select>

                <select name="revocation_reason_category" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Categories</option>
                    <option value="holder_request" {{ request('revocation_reason_category') === 'holder_request' ? 'selected' : '' }}>Holder Request</option>
                    <option value="certification_withdrawn" {{ request('revocation_reason_category') === 'certification_withdrawn' ? 'selected' : '' }}>Certification Withdrawn</option>
                    <option value="fraud_misconduct" {{ request('revocation_reason_category') === 'fraud_misconduct' ? 'selected' : '' }}>Fraud/Misconduct</option>
                    <option value="competency_loss" {{ request('revocation_reason_category') === 'competency_loss' ? 'selected' : '' }}>Competency Loss</option>
                    <option value="non_compliance" {{ request('revocation_reason_category') === 'non_compliance' ? 'selected' : '' }}>Non-Compliance</option>
                    <option value="superseded" {{ request('revocation_reason_category') === 'superseded' ? 'selected' : '' }}>Superseded</option>
                    <option value="administrative" {{ request('revocation_reason_category') === 'administrative' ? 'selected' : '' }}>Administrative</option>
                    <option value="other" {{ request('revocation_reason_category') === 'other' ? 'selected' : '' }}>Other</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.certificate-revoke.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Revocations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Revocation Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Revocation Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($revocations as $revocation)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-red-600 text-3xl">block</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $revocation->revocation_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">ID: #{{ $revocation->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $revocation->certificate->certificate_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $revocation->certificate->holder_name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $revocation->certificate->assessee->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                    {{ str_replace('_', ' ', ucfirst($revocation->revocation_reason_category)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $revocation->revocation_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'approved' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Approved'],
                                        'rejected' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Rejected'],
                                        'appealed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Appealed'],
                                        'withdrawn' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Withdrawn'],
                                    ];
                                    $config = $statusConfig[$revocation->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $revocation->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificate-revoke.show', $revocation) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>

                                    @if($revocation->status === 'pending')
                                        <a href="{{ route('admin.certificate-revoke.edit', $revocation) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
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
                                    <span class="material-symbols-outlined text-6xl text-gray-300">block</span>
                                    <p class="text-gray-500">No revocation requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($revocations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $revocations->links() }}
            </div>
        @endif
    </div>
@endsection
