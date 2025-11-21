@extends('layouts.admin')

@section('title', 'Edit Profil')

@php
    $active = 'profile';
@endphp

@section('page_title', 'Edit Profil')
@section('page_description', 'Kelola informasi akun Anda')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Left Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-900">person</span>
                            Informasi Pribadi
                        </h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap *
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email *
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror"
                                placeholder="email@example.com"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-900">lock</span>
                            Ubah Password
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Saat Ini
                            </label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_password') border-red-500 @enderror"
                                placeholder="Masukkan password saat ini"
                            >
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <input
                                type="password"
                                id="new_password"
                                name="new_password"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('new_password') border-red-500 @enderror"
                                placeholder="Minimal 8 karakter"
                            >
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input
                                type="password"
                                id="new_password_confirmation"
                                name="new_password_confirmation"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="Ulangi password baru"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Right Column (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Account Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-900">info</span>
                        Informasi Akun
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Akun dibuat:</span>
                            <p class="font-semibold text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            <p class="font-semibold text-green-600">Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Password Requirements Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 text-xl">security</span>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-900 mb-3">Ketentuan Password:</h4>
                            <ul class="text-sm text-blue-800 space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-base mt-0.5">check_circle</span>
                                    <span>Minimal 8 karakter</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-base mt-0.5">check_circle</span>
                                    <span>Password baru harus sama dengan konfirmasi</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-base mt-0.5">check_circle</span>
                                    <span>Wajib memasukkan password saat ini</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition"
                        >
                            <span class="material-symbols-outlined">save</span>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition"
                        >
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Batal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
