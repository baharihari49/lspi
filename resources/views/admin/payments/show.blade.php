@extends('layouts.admin')

@section('title', 'Payment Details')

@php
    $active = 'payments';
@endphp

@section('page_title', $payment->payment_number)
@section('page_description', 'Payment transaction details')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Payment Information</h3>
                    @if($payment->isPending())
                        <a href="{{ route('admin.payments.edit', $payment) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Payment Number</label>
                        <p class="mt-1 text-gray-900 font-medium">{{ $payment->payment_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Invoice Date</label>
                        <p class="mt-1 text-gray-900">{{ $payment->invoice_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Due Date</label>
                        <p class="mt-1 text-gray-900">{{ $payment->due_date->format('d F Y') }}</p>
                        @if($payment->isOverdue())
                            <span class="text-xs text-red-600 font-medium">Overdue</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <div class="mt-1">
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
                        </div>
                    </div>
                    @if($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Paid At</label>
                            <p class="mt-1 text-gray-900">{{ $payment->paid_at->format('d F Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Payer Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="mt-1 text-gray-900 font-medium">{{ $payment->payer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type</label>
                        <p class="mt-1 text-gray-900">{{ ucfirst($payment->payer_type) }}</p>
                    </div>
                    @if($payment->payer_email)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $payment->payer_email }}</p>
                        </div>
                    @endif
                    @if($payment->payer_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-gray-900">{{ $payment->payer_phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Items</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-gray-200">
                            <tr>
                                <th class="text-left py-3 text-sm font-semibold text-gray-600">Item</th>
                                <th class="text-center py-3 text-sm font-semibold text-gray-600">Qty</th>
                                <th class="text-right py-3 text-sm font-semibold text-gray-600">Unit Price</th>
                                <th class="text-right py-3 text-sm font-semibold text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payment->items as $item)
                                <tr>
                                    <td class="py-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                                        @if($item->description)
                                            <p class="text-xs text-gray-500">{{ $item->description }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="text-right py-3 text-sm text-gray-900">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 text-sm font-medium text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($payment->admin_fee > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Admin Fee</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($payment->admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($payment->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount @if($payment->discount_code)({{ $payment->discount_code }})@endif</span>
                            <span class="font-medium">- Rp {{ number_format($payment->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($payment->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($payment->tax_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                        <span class="text-gray-900">Total Amount</span>
                        <span class="text-blue-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($payment->paid_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Paid Amount</span>
                            <span class="font-medium text-green-600">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($payment->getRemainingAmount() > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Remaining</span>
                                <span class="font-medium text-red-600">Rp {{ number_format($payment->getRemainingAmount(), 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Status History -->
            @if($payment->statusHistory->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status History</h3>

                    <div class="space-y-4">
                        @foreach($payment->statusHistory as $history)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-{{ $history->getColor() }}-100 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-{{ $history->getColor() }}-600 text-xl">{{ $history->getIcon() }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-8">
                                    <p class="text-sm font-medium text-gray-900">{{ $history->getChangeDescription() }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $history->getChangeReason() }}</p>
                                    <div class="flex items-center gap-2 mt-2 text-xs text-gray-400">
                                        <span>{{ $history->getChangedByName() }}</span>
                                        <span>"</span>
                                        <span>{{ $history->getTimeAgo() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Payment Method Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Method</h3>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Method</p>
                        <p class="font-medium text-gray-900">{{ $payment->paymentMethod->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="text-sm text-gray-900">{{ ucfirst($payment->paymentMethod->category) }}</p>
                    </div>
                    @if($payment->payment_channel)
                        <div>
                            <p class="text-sm text-gray-500">Channel</p>
                            <p class="text-sm text-gray-900">{{ $payment->payment_channel }}</p>
                        </div>
                    @endif
                    @if($payment->transaction_id)
                        <div>
                            <p class="text-sm text-gray-500">Transaction ID</p>
                            <p class="text-xs font-mono text-gray-900">{{ $payment->transaction_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($payment->isPending())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <form action="{{ route('admin.payments.mark-paid', $payment) }}" method="POST" onsubmit="return confirm('Mark this payment as paid?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                Mark as Paid
                            </button>
                        </form>

                        <form action="{{ route('admin.payments.cancel', $payment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this payment?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                Cancel Payment
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Payment Proof -->
            @if($payment->hasProof())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Proof</h3>

                    <a href="{{ $payment->getProofUrl() }}" target="_blank" class="block">
                        <img src="{{ $payment->getProofUrl() }}" alt="Payment Proof" class="w-full rounded-lg border border-gray-200">
                    </a>

                    @if($payment->verified_at)
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">
                                <span class="font-medium">Verified by {{ $payment->verifiedBy->name }}</span><br>
                                <span class="text-xs">{{ $payment->verified_at->format('d M Y H:i') }}</span>
                            </p>
                        </div>
                    @else
                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                                Verify Payment
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Notes -->
            @if($payment->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>
                    <p class="text-sm text-gray-600">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
