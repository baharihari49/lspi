@extends('layouts.admin')

@section('title', 'Payments')

@php
    $active = 'payments';
@endphp

@section('page_title', 'Payments')
@section('page_description', 'Manage payment transactions and invoices')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment number, payer name..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>

                <select name="payment_method" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Payment Methods</option>
                    @foreach($paymentMethods ?? [] as $method)
                        <option value="{{ $method->id }}" {{ request('payment_method') == $method->id ? 'selected' : '' }}>
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">

                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                    <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">schedule</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Paid</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['paid'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">hourglass_empty</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Partial</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['partial'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">error</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Overdue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600">payments</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payer</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">receipt_long</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $payment->payment_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $payment->paymentMethod->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $payment->invoice_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $payment->payer_name }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->payer_email }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                @if($payment->paid_amount > 0 && $payment->paid_amount < $payment->total_amount)
                                    <p class="text-xs text-gray-500">Paid: Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Paid'],
                                        'partial' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Partial'],
                                        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Failed'],
                                        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Cancelled'],
                                        'refunded' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Refunded'],
                                    ];
                                    $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $payment->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm text-gray-900">{{ $payment->due_date->format('d M Y') }}</p>
                                @if($payment->isOverdue())
                                    <p class="text-xs text-red-600 font-medium">Overdue</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="View Details">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                    @if($payment->isPending())
                                        <a href="{{ route('admin.payments.edit', $payment) }}"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                           title="Edit">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">receipt_long</span>
                                    <p class="text-gray-500 text-lg font-medium">No payments found</p>
                                    <p class="text-gray-400 text-sm mt-1">Payments will appear here once created</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
