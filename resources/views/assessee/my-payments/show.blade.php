@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@php $active = 'my-payments'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-payments.index') }}" class="hover:text-blue-600">Pembayaran Saya</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>{{ $payment->invoice_number ?? $payment->payment_number }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
        </div>
        @if($payment->status === 'pending')
            <a href="{{ route('admin.my-payments.upload-proof', $payment) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <span class="material-symbols-outlined text-lg">upload</span>
                Upload Bukti Bayar
            </a>
        @endif
    </div>

    <!-- Status Banner -->
    @if($payment->status === 'pending')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-yellow-600">warning</span>
                <div>
                    <h3 class="font-semibold text-yellow-800">Menunggu Pembayaran</h3>
                    <p class="text-sm text-yellow-700 mt-1">Silakan lakukan pembayaran dan upload bukti transfer.</p>
                </div>
            </div>
        </div>
    @elseif($payment->status === 'pending_verification')
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600">hourglass_top</span>
                <div>
                    <h3 class="font-semibold text-blue-800">Menunggu Verifikasi</h3>
                    <p class="text-sm text-blue-700 mt-1">Bukti pembayaran Anda sedang diverifikasi oleh admin.</p>
                </div>
            </div>
        </div>
    @elseif($payment->status === 'paid')
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <div>
                    <h3 class="font-semibold text-green-800">Pembayaran Berhasil</h3>
                    <p class="text-sm text-green-700 mt-1">Pembayaran telah dikonfirmasi. Terima kasih!</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Invoice</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Invoice</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $payment->invoice_number ?? $payment->payment_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-medium text-gray-900">{{ $payment->created_at?->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                    @if($payment->description)
                        <div>
                            <p class="text-sm text-gray-600">Deskripsi</p>
                            <p class="font-medium text-gray-900">{{ $payment->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Items -->
                @if($payment->items && $payment->items->count() > 0)
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h3 class="font-medium text-gray-900 mb-3">Item Pembayaran</h3>
                        <div class="space-y-2">
                            @foreach($payment->items as $item)
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="text-gray-900">{{ $item->item_name }}</p>
                                        @if($item->item_description)
                                            <p class="text-sm text-gray-500">{{ $item->item_description }}</p>
                                        @endif
                                    </div>
                                    <p class="font-medium text-gray-900">Rp {{ number_format($item->amount, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Totals -->
                <div class="mt-6 border-t border-gray-200 pt-4 space-y-2">
                    @if($payment->subtotal)
                        <div class="flex items-center justify-between">
                            <p class="text-gray-600">Subtotal</p>
                            <p class="font-medium text-gray-900">Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    @if($payment->discount_amount && $payment->discount_amount > 0)
                        <div class="flex items-center justify-between text-green-600">
                            <p>Diskon</p>
                            <p class="font-medium">- Rp {{ number_format($payment->discount_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    @if($payment->tax_amount && $payment->tax_amount > 0)
                        <div class="flex items-center justify-between">
                            <p class="text-gray-600">Pajak</p>
                            <p class="font-medium text-gray-900">Rp {{ number_format($payment->tax_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                        <p class="text-lg font-semibold text-gray-900">Total</p>
                        <p class="text-xl font-bold text-blue-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            @if($payment->payment_proof_url)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Bukti Pembayaran</h2>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">image</span>
                                <div>
                                    <p class="font-medium text-gray-900">Bukti Transfer</p>
                                    <p class="text-sm text-gray-500">Diupload: {{ $payment->payment_proof_uploaded_at?->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <a href="{{ $payment->payment_proof_url }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <span class="material-symbols-outlined text-lg">open_in_new</span>
                                Lihat
                            </a>
                        </div>
                    </div>
                    @if($payment->payer_notes)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Catatan:</p>
                            <p class="text-gray-700">{{ $payment->payer_notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Method -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
                @if($payment->paymentMethod)
                    <div class="flex items-center gap-3">
                        @if($payment->paymentMethod->logo_url)
                            <img src="{{ $payment->paymentMethod->logo_url }}" alt="{{ $payment->paymentMethod->name }}" class="h-10 w-auto">
                        @else
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <span class="material-symbols-outlined text-gray-600">account_balance</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $payment->paymentMethod->name }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->paymentMethod->category }}</p>
                        </div>
                    </div>
                    @if($payment->paymentMethod->account_number)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Nomor Rekening</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $payment->paymentMethod->account_number }}</p>
                            <p class="text-sm text-gray-600">a.n. {{ $payment->paymentMethod->account_name }}</p>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500">Metode pembayaran tidak tersedia</p>
                @endif
            </div>

            <!-- Status History -->
            @if($payment->statusHistory && $payment->statusHistory->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Status</h2>
                    <div class="space-y-4">
                        @foreach($payment->statusHistory->sortByDesc('created_at') as $history)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->to_status)) }}</p>
                                    <p class="text-sm text-gray-500">{{ $history->created_at?->format('d M Y H:i') }}</p>
                                    @if($history->notes)
                                        <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
