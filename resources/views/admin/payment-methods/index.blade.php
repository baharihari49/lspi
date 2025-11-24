@extends('layouts.admin')

@section('title', 'Payment Methods')

@php
    $active = 'payment-methods';
@endphp

@section('page_title', 'Payment Methods')
@section('page_description', 'Manage payment methods and settings')

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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.payment-methods.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, code, category..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.payment-methods.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Reset
                    </a>
                @endif

                <a href="{{ route('admin.payment-methods.create') }}" class="ml-auto px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    <span>Add Payment Method</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">credit_card</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Methods</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

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
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">block</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600">account_balance</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Bank Transfer</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['bank_transfer'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <span class="material-symbols-outlined text-orange-600">wallet</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">E-Wallet</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['e_wallet'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Admin Fee</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paymentMethods as $method)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                        @if($method->logo_url)
                                            <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" class="w-10 h-10 object-contain">
                                        @else
                                            <span class="material-symbols-outlined text-gray-400">credit_card</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $method->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $method->code }}</p>
                                        @if($method->requires_manual_verification)
                                            <span class="text-xs text-orange-600 font-medium">Manual Verification</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $method->category)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm text-gray-900">
                                    @if($method->admin_fee_percentage > 0)
                                        <span class="font-medium">{{ $method->admin_fee_percentage }}%</span>
                                    @endif
                                    @if($method->admin_fee_fixed > 0)
                                        @if($method->admin_fee_percentage > 0) + @endif
                                        <span class="font-medium">Rp {{ number_format($method->admin_fee_fixed, 0, ',', '.') }}</span>
                                    @endif
                                    @if($method->admin_fee_percentage == 0 && $method->admin_fee_fixed == 0)
                                        <span class="text-green-600 font-medium">Free</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.payment-methods.toggle-status', $method) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold {{ $method->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.payment-methods.show', $method) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="View Details">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.payment-methods.edit', $method) }}"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this payment method?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Delete">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">credit_card</span>
                                    <p class="text-gray-500 text-lg font-medium">No payment methods found</p>
                                    <p class="text-gray-400 text-sm mt-1">Add your first payment method to get started</p>
                                    <a href="{{ route('admin.payment-methods.create') }}" class="mt-4 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                                        Add Payment Method
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paymentMethods->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $paymentMethods->links() }}
            </div>
        @endif
    </div>
@endsection
