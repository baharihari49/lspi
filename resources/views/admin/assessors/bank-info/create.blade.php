@extends('layouts.admin')

@section('title', 'Add Bank Information')

@php
    $active = 'assessor-bank-info';
@endphp

@section('page_title', 'Add Bank Information')
@section('page_description', 'Register assessor banking and tax information')

@section('content')
    <form action="{{ route('admin.assessor-bank-info.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessor Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                    <div>
                        <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor *</label>
                        <select id="assessor_id" name="assessor_id" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                            <option value="">Select Assessor</option>
                            @foreach($assessors as $assessor)
                                <option value="{{ $assessor->id }}" {{ old('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                    {{ $assessor->full_name }} ({{ $assessor->registration_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('assessor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bank Account Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Bank Account Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Bank Name -->
                        <div class="md:col-span-2">
                            <label for="bank_name" class="block text-sm font-semibold text-gray-700 mb-2">Bank Name *</label>
                            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('bank_name') border-red-500 @enderror"
                                placeholder="e.g., Bank Mandiri, BCA, BNI">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Code -->
                        <div>
                            <label for="bank_code" class="block text-sm font-semibold text-gray-700 mb-2">Bank Code</label>
                            <input type="text" id="bank_code" name="bank_code" value="{{ old('bank_code') }}" maxlength="10"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('bank_code') border-red-500 @enderror"
                                placeholder="e.g., 008, 014">
                            @error('bank_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Branch Name -->
                        <div>
                            <label for="branch_name" class="block text-sm font-semibold text-gray-700 mb-2">Branch Name</label>
                            <input type="text" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('branch_name') border-red-500 @enderror"
                                placeholder="e.g., Jakarta Pusat, Surabaya">
                            @error('branch_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label for="account_number" class="block text-sm font-semibold text-gray-700 mb-2">Account Number *</label>
                            <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" required maxlength="50"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('account_number') border-red-500 @enderror"
                                placeholder="e.g., 1234567890">
                            @error('account_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Holder Name -->
                        <div>
                            <label for="account_holder_name" class="block text-sm font-semibold text-gray-700 mb-2">Account Holder Name *</label>
                            <input type="text" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('account_holder_name') border-red-500 @enderror"
                                placeholder="Name as shown on bank account">
                            @error('account_holder_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tax Information (NPWP) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tax Information (NPWP)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- NPWP Number -->
                        <div class="md:col-span-2">
                            <label for="npwp_number" class="block text-sm font-semibold text-gray-700 mb-2">NPWP Number</label>
                            <input type="text" id="npwp_number" name="npwp_number" value="{{ old('npwp_number') }}" maxlength="20"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('npwp_number') border-red-500 @enderror"
                                placeholder="XX.XXX.XXX.X-XXX.XXX">
                            @error('npwp_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Format: 15 digits with separators (e.g., 12.345.678.9-012.345)</p>
                        </div>

                        <!-- Tax Name -->
                        <div>
                            <label for="tax_name" class="block text-sm font-semibold text-gray-700 mb-2">Tax Name</label>
                            <input type="text" id="tax_name" name="tax_name" value="{{ old('tax_name') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tax_name') border-red-500 @enderror"
                                placeholder="Name as shown on NPWP">
                            @error('tax_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Address -->
                        <div class="md:col-span-2">
                            <label for="tax_address" class="block text-sm font-semibold text-gray-700 mb-2">Tax Address</label>
                            <textarea id="tax_address" name="tax_address" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tax_address') border-red-500 @enderror"
                                placeholder="Address as registered in NPWP">{{ old('tax_address') }}</textarea>
                            @error('tax_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Uploads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Supporting Documents</h3>

                    <div class="space-y-4">
                        <!-- Bank Statement File -->
                        <div>
                            <label for="bank_statement_file" class="block text-sm font-semibold text-gray-700 mb-2">Bank Statement File</label>
                            <input type="file" id="bank_statement_file" name="bank_statement_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('bank_statement_file') border-red-500 @enderror">
                            @error('bank_statement_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                Upload recent bank statement showing account details. Supported formats: PDF, JPG, PNG. Max size: 5MB.
                            </p>
                        </div>

                        <!-- NPWP File -->
                        <div>
                            <label for="npwp_file" class="block text-sm font-semibold text-gray-700 mb-2">NPWP Document File</label>
                            <input type="file" id="npwp_file" name="npwp_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('npwp_file') border-red-500 @enderror">
                            @error('npwp_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                Upload NPWP card or certificate. Supported formats: PDF, JPG, PNG. Max size: 5MB.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Settings</h3>

                    <div class="space-y-4">
                        <!-- Is Primary -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Primary Bank Account</p>
                                    <p class="text-xs text-gray-600">Set as the primary account for payments</p>
                                </div>
                            </label>
                        </div>

                        <!-- Is Active -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Active Account</p>
                                    <p class="text-xs text-gray-600">Account is active and can be used for transactions</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Help Information -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Bank Information Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Ensure account details are accurate</li>
                                    <li>• Upload clear bank statement and NPWP</li>
                                    <li>• Only one primary account per assessor</li>
                                    <li>• Information will be verified by admin</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Verification Status</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="text-gray-700">New bank info will be marked as <strong>Pending</strong></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                Bank information requires admin verification before it can be used for payments.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Create Bank Information</span>
                            </button>
                            <a href="{{ route('admin.assessor-bank-info.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
