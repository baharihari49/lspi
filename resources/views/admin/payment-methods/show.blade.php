@extends('layouts.admin')

@section('title', 'Payment Method Details')

@php
    $active = 'payment-methods';
@endphp

@section('page_title', 'Payment Method Details')
@section('page_description', 'View payment method information')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center gap-2 text-blue-900 hover:text-blue-700 font-medium">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Payment Methods</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Code</p>
                            <p class="font-medium text-gray-900">{{ $paymentMethod->code }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Name</p>
                            <p class="font-medium text-gray-900">{{ $paymentMethod->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Category</p>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                {{ ucfirst(str_replace('_', ' ', $paymentMethod->category)) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentMethod->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if($paymentMethod->description)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Description</p>
                            <p class="text-gray-900">{{ $paymentMethod->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bank/Provider Information -->
            @if($paymentMethod->bank_name || $paymentMethod->provider_name || $paymentMethod->account_number)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Bank/Provider Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($paymentMethod->bank_name)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Bank Name</p>
                                <p class="font-medium text-gray-900">{{ $paymentMethod->bank_name }}</p>
                            </div>
                        @endif

                        @if($paymentMethod->provider_name)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Provider Name</p>
                                <p class="font-medium text-gray-900">{{ $paymentMethod->provider_name }}</p>
                            </div>
                        @endif

                        @if($paymentMethod->account_number)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Account Number</p>
                                <p class="font-medium text-gray-900">{{ $paymentMethod->account_number }}</p>
                            </div>
                        @endif

                        @if($paymentMethod->account_holder_name)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Account Holder Name</p>
                                <p class="font-medium text-gray-900">{{ $paymentMethod->account_holder_name }}</p>
                            </div>
                        @endif

                        @if($paymentMethod->gateway_code)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Gateway Code</p>
                                <p class="font-medium text-gray-900">{{ $paymentMethod->gateway_code }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Fee Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fee Settings</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Admin Fee</p>
                        <div class="font-medium text-gray-900">
                            @if($paymentMethod->admin_fee_percentage > 0)
                                <span>{{ $paymentMethod->admin_fee_percentage }}%</span>
                            @endif
                            @if($paymentMethod->admin_fee_fixed > 0)
                                @if($paymentMethod->admin_fee_percentage > 0) + @endif
                                <span>Rp {{ number_format($paymentMethod->admin_fee_fixed, 0, ',', '.') }}</span>
                            @endif
                            @if($paymentMethod->admin_fee_percentage == 0 && $paymentMethod->admin_fee_fixed == 0)
                                <span class="text-green-600">Free</span>
                            @endif
                        </div>
                    </div>

                    @if($paymentMethod->min_amount)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Min Amount</p>
                            <p class="font-medium text-gray-900">Rp {{ number_format($paymentMethod->min_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif

                    @if($paymentMethod->max_amount)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Max Amount</p>
                            <p class="font-medium text-gray-900">Rp {{ number_format($paymentMethod->max_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Display Order</p>
                        <p class="font-medium text-gray-900">{{ $paymentMethod->display_order }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($paymentMethod->instructions)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Instructions</h3>
                    <div class="text-gray-900 whitespace-pre-line">{{ $paymentMethod->instructions }}</div>
                </div>
            @endif

            <!-- Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Settings</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Manual Verification Required</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentMethod->requires_manual_verification ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                            {{ $paymentMethod->requires_manual_verification ? 'Yes' : 'No' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600">Active Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentMethod->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Logo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Logo</h3>

                <div class="flex items-center justify-center p-6 bg-gray-50 rounded-lg">
                    @if($paymentMethod->logo_url)
                        <img src="{{ $paymentMethod->logo_url }}" alt="{{ $paymentMethod->name }}" class="max-w-full max-h-32 object-contain">
                    @else
                        <div class="text-center">
                            <span class="material-symbols-outlined text-gray-400 text-6xl">credit_card</span>
                            <p class="text-sm text-gray-500 mt-2">No logo</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}"
                        class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit</span>
                    </a>

                    <form action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 {{ $paymentMethod->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition font-medium flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">{{ $paymentMethod->is_active ? 'block' : 'check_circle' }}</span>
                            <span>{{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }}</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this payment method? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">delete</span>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Information</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Created</p>
                        <p class="font-medium text-gray-900">{{ $paymentMethod->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Last Updated</p>
                        <p class="font-medium text-gray-900">{{ $paymentMethod->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
