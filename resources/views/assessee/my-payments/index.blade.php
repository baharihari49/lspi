@extends('layouts.admin')

@section('title', 'Pembayaran Saya')

@php $active = 'my-payments'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Saya</h1>
            <p class="text-gray-600 mt-1">Riwayat pembayaran biaya sertifikasi</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">receipt_long</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Transaksi</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">pending</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Lunas</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['paid'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600">account_balance_wallet</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Dibayar</p>
                    <p class="text-lg font-bold text-purple-600">Rp {{ number_format($stats['total_paid'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <span class="material-symbols-outlined text-sm align-middle">filter_list</span>
                Filter
            </button>
        </form>
    </div>

    <!-- Payments List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($payments->isEmpty())
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-gray-300 text-6xl">payments</span>
                <p class="mt-4 text-gray-500">Belum ada pembayaran</p>
                <p class="text-sm text-gray-400">Riwayat pembayaran akan muncul di sini</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-medium text-blue-600">{{ $payment->invoice_number ?? $payment->payment_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900">{{ $payment->description ?? 'Biaya Sertifikasi' }}</p>
                                    @if($payment->paymentMethod)
                                        <p class="text-sm text-gray-500">{{ $payment->paymentMethod->name }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $payment->created_at?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'pending_verification' => 'bg-blue-100 text-blue-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                            'refunded' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'pending_verification' => 'Menunggu Verifikasi',
                                            'paid' => 'Lunas',
                                            'failed' => 'Gagal',
                                            'cancelled' => 'Dibatalkan',
                                            'refunded' => 'Refund',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$payment->status] ?? $payment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.my-payments.show', $payment) }}"
                                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </a>
                                        @if($payment->status === 'pending')
                                            <a href="{{ route('admin.my-payments.upload-proof', $payment) }}"
                                                class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Upload Bukti">
                                                <span class="material-symbols-outlined text-xl">upload</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
