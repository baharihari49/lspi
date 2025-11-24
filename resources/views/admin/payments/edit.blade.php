@extends('layouts.admin')

@section('title', 'Edit Payment')

@php
    $active = 'payments';
@endphp

@section('page_title', 'Edit Payment')
@section('page_description', 'Update payment transaction details')

@section('content')
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
                <span class="material-symbols-outlined text-red-600 mr-3">error</span>
                <div class="flex-1">
                    <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.payments.update', $payment) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Number -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-900 text-3xl">receipt_long</span>
                        <div>
                            <p class="text-sm text-gray-500">Payment Number</p>
                            <p class="text-xl font-bold text-gray-900">{{ $payment->payment_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payer Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payer Type <span class="text-red-600">*</span></label>
                            <select name="payer_type" id="payerType" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select payer type</option>
                                <option value="assessee" {{ old('payer_type', $payment->payer_type) === 'assessee' ? 'selected' : '' }}>Assessee</option>
                                <option value="organization" {{ old('payer_type', $payment->payer_type) === 'organization' ? 'selected' : '' }}>Organization</option>
                                <option value="individual" {{ old('payer_type', $payment->payer_type) === 'individual' ? 'selected' : '' }}>Individual</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payer Name <span class="text-red-600">*</span></label>
                                <input type="text" name="payer_name" value="{{ old('payer_name', $payment->payer_name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payer Email</label>
                                <input type="email" name="payer_email" value="{{ old('payer_email', $payment->payer_email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payer Phone</label>
                                <input type="text" name="payer_phone" value="{{ old('payer_phone', $payment->payer_phone) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payer Address</label>
                                <input type="text" name="payer_address" value="{{ old('payer_address', $payment->payer_address) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Details</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment For <span class="text-red-600">*</span></label>
                            <select name="payable_type" id="payableType" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select payment type</option>
                                <option value="event" {{ old('payable_type', $payment->payable_type) === 'event' ? 'selected' : '' }}>Event</option>
                                <option value="assessment" {{ old('payable_type', $payment->payable_type) === 'assessment' ? 'selected' : '' }}>Assessment</option>
                                <option value="certificate_renewal" {{ old('payable_type', $payment->payable_type) === 'certificate_renewal' ? 'selected' : '' }}>Certificate Renewal</option>
                                <option value="other" {{ old('payable_type', $payment->payable_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date <span class="text-red-600">*</span></label>
                                <input type="date" name="invoice_date" value="{{ old('invoice_date', $payment->invoice_date->format('Y-m-d')) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date <span class="text-red-600">*</span></label>
                                <input type="date" name="due_date" value="{{ old('due_date', $payment->due_date->format('Y-m-d')) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('notes', $payment->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Payment Items</h3>
                        <button type="button" onclick="addPaymentItem()"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">add</span>
                            <span>Add Item</span>
                        </button>
                    </div>

                    <div id="paymentItems" class="space-y-4">
                        <!-- Existing payment items -->
                        @foreach($payment->items as $index => $item)
                            <div class="payment-item border border-gray-200 rounded-lg p-4" data-item="{{ $index }}" data-existing="true" data-item-id="{{ $item->id }}">
                                <div class="flex items-start justify-between mb-4">
                                    <h4 class="font-medium text-gray-900">Item #{{ $index + 1 }}</h4>
                                    <button type="button" onclick="removePaymentItem({{ $index }})" class="text-red-600 hover:text-red-700">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>

                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Item Type</label>
                                        <select name="items[{{ $index }}][item_type]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                                            <option value="event_fee" {{ $item->item_type === 'event_fee' ? 'selected' : '' }}>Event Fee</option>
                                            <option value="assessment_fee" {{ $item->item_type === 'assessment_fee' ? 'selected' : '' }}>Assessment Fee</option>
                                            <option value="certificate_fee" {{ $item->item_type === 'certificate_fee' ? 'selected' : '' }}>Certificate Fee</option>
                                            <option value="renewal_fee" {{ $item->item_type === 'renewal_fee' ? 'selected' : '' }}>Renewal Fee</option>
                                            <option value="registration_fee" {{ $item->item_type === 'registration_fee' ? 'selected' : '' }}>Registration Fee</option>
                                            <option value="material_fee" {{ $item->item_type === 'material_fee' ? 'selected' : '' }}>Material Fee</option>
                                            <option value="other" {{ $item->item_type === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Item Name</label>
                                        <input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                        <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required
                                                class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                                onchange="calculateTotals()">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price (Rp)</label>
                                            <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01" required
                                                class="item-price w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                                onchange="calculateTotals()">
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-gray-200">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Item Total:</span>
                                            <span class="font-medium text-gray-900 item-total">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900" id="subtotalDisplay">Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Admin Fee</span>
                            <span class="font-medium text-gray-900" id="adminFeeDisplay">Rp {{ number_format($payment->admin_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600" id="discountDisplay">- Rp {{ number_format($payment->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900" id="taxDisplay">Rp {{ number_format($payment->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Total Amount</span>
                            <span class="text-blue-900" id="totalDisplay">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotalInput" value="{{ $payment->subtotal }}">
                    <input type="hidden" name="admin_fee" id="adminFeeInput" value="{{ $payment->admin_fee }}">
                    <input type="hidden" name="discount_amount" id="discountInput" value="{{ $payment->discount_amount }}">
                    <input type="hidden" name="tax_amount" id="taxInput" value="{{ $payment->tax_amount }}">
                    <input type="hidden" name="total_amount" id="totalInput" value="{{ $payment->total_amount }}">
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Method</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Method <span class="text-red-600">*</span></label>
                            <select name="payment_method_id" id="paymentMethod" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select payment method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        data-admin-fee-percentage="{{ $method->admin_fee_percentage }}"
                                        data-admin-fee-fixed="{{ $method->admin_fee_fixed }}"
                                        {{ old('payment_method_id', $payment->payment_method_id) == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }} ({{ ucfirst($method->category) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Settings</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Code</label>
                            <input type="text" name="discount_code" value="{{ old('discount_code', $payment->discount_code) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage (%)</label>
                            <input type="number" name="discount_percentage" id="discountPercentage" value="{{ old('discount_percentage', $payment->discount_percentage ?? 0) }}" min="0" max="100" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Percentage (%)</label>
                            <input type="number" name="tax_percentage" id="taxPercentage" value="{{ old('tax_percentage', $payment->tax_percentage ?? 0) }}" min="0" max="100" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                            Update Payment
                        </button>

                        <a href="{{ route('admin.payments.show', $payment) }}"
                            class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        let itemCounter = {{ $payment->items->count() }};

        // Add payment item
        function addPaymentItem() {
            itemCounter++;
            const itemHtml = `
                <div class="payment-item border border-gray-200 rounded-lg p-4" data-item="${itemCounter}">
                    <div class="flex items-start justify-between mb-4">
                        <h4 class="font-medium text-gray-900">Item #${itemCounter + 1}</h4>
                        <button type="button" onclick="removePaymentItem(${itemCounter})" class="text-red-600 hover:text-red-700">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Item Type</label>
                            <select name="items[${itemCounter}][item_type]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                                <option value="event_fee">Event Fee</option>
                                <option value="assessment_fee">Assessment Fee</option>
                                <option value="certificate_fee">Certificate Fee</option>
                                <option value="renewal_fee">Renewal Fee</option>
                                <option value="registration_fee">Registration Fee</option>
                                <option value="material_fee">Material Fee</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Item Name</label>
                            <input type="text" name="items[${itemCounter}][item_name]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="items[${itemCounter}][description]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="items[${itemCounter}][quantity]" value="1" min="1" required
                                    class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                    onchange="calculateTotals()">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price (Rp)</label>
                                <input type="number" name="items[${itemCounter}][unit_price]" value="0" min="0" step="0.01" required
                                    class="item-price w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Item Total:</span>
                                <span class="font-medium text-gray-900 item-total">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('paymentItems').insertAdjacentHTML('beforeend', itemHtml);
            calculateTotals();
        }

        // Remove payment item
        function removePaymentItem(itemId) {
            const item = document.querySelector(`[data-item="${itemId}"]`);
            if (item) {
                // If it's an existing item, mark it for deletion
                if (item.dataset.existing === 'true') {
                    const deletedInput = document.createElement('input');
                    deletedInput.type = 'hidden';
                    deletedInput.name = 'deleted_items[]';
                    deletedInput.value = item.dataset.itemId;
                    document.getElementById('paymentForm').appendChild(deletedInput);
                }
                item.remove();
                calculateTotals();
            }
        }

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;

            // Calculate subtotal from items
            document.querySelectorAll('.payment-item').forEach(item => {
                const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
                const price = parseFloat(item.querySelector('.item-price').value) || 0;
                const itemTotal = quantity * price;

                item.querySelector('.item-total').textContent = formatCurrency(itemTotal);
                subtotal += itemTotal;
            });

            // Get payment method admin fee
            const paymentMethodSelect = document.getElementById('paymentMethod');
            const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const adminFeePercentage = parseFloat(selectedOption.dataset.adminFeePercentage || 0);
            const adminFeeFixed = parseFloat(selectedOption.dataset.adminFeeFixed || 0);
            const adminFee = (subtotal * adminFeePercentage / 100) + adminFeeFixed;

            // Get discount
            const discountPercentage = parseFloat(document.getElementById('discountPercentage').value) || 0;
            const discountAmount = subtotal * discountPercentage / 100;

            // Get tax
            const taxPercentage = parseFloat(document.getElementById('taxPercentage').value) || 0;
            const taxAmount = (subtotal + adminFee - discountAmount) * taxPercentage / 100;

            // Calculate total
            const total = subtotal + adminFee - discountAmount + taxAmount;

            // Update displays
            document.getElementById('subtotalDisplay').textContent = formatCurrency(subtotal);
            document.getElementById('adminFeeDisplay').textContent = formatCurrency(adminFee);
            document.getElementById('discountDisplay').textContent = '- ' + formatCurrency(discountAmount);
            document.getElementById('taxDisplay').textContent = formatCurrency(taxAmount);
            document.getElementById('totalDisplay').textContent = formatCurrency(total);

            // Update hidden inputs
            document.getElementById('subtotalInput').value = subtotal.toFixed(2);
            document.getElementById('adminFeeInput').value = adminFee.toFixed(2);
            document.getElementById('discountInput').value = discountAmount.toFixed(2);
            document.getElementById('taxInput').value = taxAmount.toFixed(2);
            document.getElementById('totalInput').value = total.toFixed(2);
        }

        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        // Event listeners
        document.getElementById('paymentMethod').addEventListener('change', calculateTotals);
        document.getElementById('discountPercentage').addEventListener('input', calculateTotals);
        document.getElementById('taxPercentage').addEventListener('input', calculateTotals);

        // Calculate totals on load
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotals();
        });
    </script>
    @endpush
@endsection
