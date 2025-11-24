@extends('layouts.admin')

@section('title', 'Edit Payment Method')

@php
    $active = 'payment-methods';
@endphp

@section('page_title', 'Edit Payment Method')
@section('page_description', 'Update payment method details')

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

    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Code <span class="text-red-600">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $paymentMethod->code) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., bca_transfer">
                                <p class="text-xs text-gray-500 mt-1">Unique identifier (lowercase, use underscore)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-600">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., Transfer Bank BCA">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-600">*</span></label>
                            <select name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select category</option>
                                <option value="bank_transfer" {{ old('category', $paymentMethod->category) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="e_wallet" {{ old('category', $paymentMethod->category) === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="qris" {{ old('category', $paymentMethod->category) === 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="virtual_account" {{ old('category', $paymentMethod->category) === 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                                <option value="credit_card" {{ old('category', $paymentMethod->category) === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="debit_card" {{ old('category', $paymentMethod->category) === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="cash" {{ old('category', $paymentMethod->category) === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="other" {{ old('category', $paymentMethod->category) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="Brief description of this payment method">{{ old('description', $paymentMethod->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Bank/Provider Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Bank/Provider Information</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., Bank Central Asia">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provider Name</label>
                                <input type="text" name="provider_name" value="{{ old('provider_name', $paymentMethod->provider_name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., GoPay, OVO">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., 1234567890">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                                <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $paymentMethod->account_holder_name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                    placeholder="e.g., LSP Pustaka Ilmiah Elektronik">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gateway Code</label>
                            <input type="text" name="gateway_code" value="{{ old('gateway_code', $paymentMethod->gateway_code) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="e.g., midtrans, xendit">
                            <p class="text-xs text-gray-500 mt-1">Payment gateway identifier for API integration</p>
                        </div>
                    </div>
                </div>

                <!-- Fee Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Fee Settings</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Fee Percentage (%) <span class="text-red-600">*</span></label>
                                <input type="number" name="admin_fee_percentage" value="{{ old('admin_fee_percentage', $paymentMethod->admin_fee_percentage) }}" step="0.01" min="0" max="100" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Fee Fixed (Rp) <span class="text-red-600">*</span></label>
                                <input type="number" name="admin_fee_fixed" value="{{ old('admin_fee_fixed', $paymentMethod->admin_fee_fixed) }}" step="1" min="0" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount (Rp)</label>
                                <input type="number" name="min_amount" value="{{ old('min_amount', $paymentMethod->min_amount) }}" step="1" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount (Rp)</label>
                                <input type="number" name="max_amount" value="{{ old('max_amount', $paymentMethod->max_amount) }}" step="1" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Instructions</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                        <textarea name="instructions" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            placeholder="Step-by-step payment instructions for users">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Logo -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Logo</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Logo</label>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <p class="text-xs text-gray-500 mt-1">Max 2MB (JPEG, PNG, JPG, SVG)</p>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Settings</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order <span class="text-red-600">*</span></label>
                            <input type="number" name="display_order" value="{{ old('display_order', $paymentMethod->display_order) }}" min="1" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_manual_verification" id="requires_manual_verification" value="1" {{ old('requires_manual_verification', $paymentMethod->requires_manual_verification) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <label for="requires_manual_verification" class="text-sm font-medium text-gray-700">Requires Manual Verification</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                            Update Payment Method
                        </button>

                        <a href="{{ route('admin.payment-methods.show', $paymentMethod) }}"
                            class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
