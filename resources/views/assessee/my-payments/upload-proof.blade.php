@extends('layouts.admin')

@section('title', 'Upload Bukti Pembayaran')

@php $active = 'my-payments'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.my-payments.index') }}" class="hover:text-blue-600">Pembayaran Saya</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.my-payments.show', $payment) }}" class="hover:text-blue-600">{{ $payment->invoice_number ?? $payment->payment_number }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span>Upload Bukti</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Upload Bukti Pembayaran</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.my-payments.store-proof', $payment) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <!-- Payment Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3">Ringkasan Pembayaran</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Nomor Invoice</p>
                                    <p class="font-mono font-medium text-gray-900">{{ $payment->invoice_number ?? $payment->payment_number }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Total Pembayaran</p>
                                    <p class="font-bold text-blue-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition @error('payment_proof') border-red-500 @enderror">
                                <input type="file" name="payment_proof" id="payment_proof" required
                                    class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                                <label for="payment_proof" class="cursor-pointer">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">cloud_upload</span>
                                    <p class="mt-2 text-gray-600">Klik untuk memilih file atau drag & drop</p>
                                    <p class="text-sm text-gray-400 mt-1">JPG, PNG, atau PDF (Max 5MB)</p>
                                </label>
                                <p id="file-name" class="mt-2 text-sm text-blue-600 font-medium"></p>
                            </div>
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transfer <span class="text-red-500">*</span></label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-500 @enderror">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sender Account Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim <span class="text-red-500">*</span></label>
                            <input type="text" name="sender_name" value="{{ old('sender_name') }}" required
                                placeholder="Nama pemilik rekening pengirim"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sender_name') border-red-500 @enderror">
                            @error('sender_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sender Bank -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bank Pengirim <span class="text-red-500">*</span></label>
                            <select name="sender_bank" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sender_bank') border-red-500 @enderror">
                                <option value="">Pilih Bank</option>
                                <option value="BCA" {{ old('sender_bank') === 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="BNI" {{ old('sender_bank') === 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI" {{ old('sender_bank') === 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="Mandiri" {{ old('sender_bank') === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BSI" {{ old('sender_bank') === 'BSI' ? 'selected' : '' }}>BSI</option>
                                <option value="CIMB Niaga" {{ old('sender_bank') === 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="Permata" {{ old('sender_bank') === 'Permata' ? 'selected' : '' }}>Permata</option>
                                <option value="Danamon" {{ old('sender_bank') === 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                <option value="Other" {{ old('sender_bank') === 'Other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('sender_bank')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="payer_notes" rows="3"
                                placeholder="Tambahkan catatan jika diperlukan..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payer_notes') border-red-500 @enderror">{{ old('payer_notes') }}</textarea>
                            @error('payer_notes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.my-payments.show', $payment) }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                Upload Bukti
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Transfer Details -->
            @if($payment->paymentMethod)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Transfer ke Rekening</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            @if($payment->paymentMethod->logo_url)
                                <img src="{{ $payment->paymentMethod->logo_url }}" alt="{{ $payment->paymentMethod->name }}" class="h-10 w-auto">
                            @else
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <span class="material-symbols-outlined text-blue-600">account_balance</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $payment->paymentMethod->name }}</p>
                            </div>
                        </div>
                        @if($payment->paymentMethod->account_number)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Nomor Rekening</p>
                                <p class="font-mono text-xl font-bold text-gray-900">{{ $payment->paymentMethod->account_number }}</p>
                                <p class="text-sm text-gray-600 mt-1">a.n. {{ $payment->paymentMethod->account_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Transfer Amount -->
            <div class="bg-blue-50 rounded-xl p-6">
                <h3 class="font-semibold text-blue-900 mb-3">Jumlah Transfer</h3>
                <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                <p class="text-sm text-blue-700 mt-2">Pastikan jumlah transfer sesuai dengan nominal di atas.</p>
            </div>

            <!-- Tips -->
            <div class="bg-yellow-50 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-900 mb-3">Tips Upload Bukti</h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Pastikan bukti transfer terlihat jelas
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Tanggal dan jumlah transfer harus terbaca
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Screenshot atau foto struk transfer
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Verifikasi membutuhkan 1x24 jam kerja
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('payment_proof').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('file-name').textContent = fileName;
});
</script>
@endpush
@endsection
