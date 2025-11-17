<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - LSP Pustaka Ilmiah Elektronik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        body {
            font-family: 'Public Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-900 px-8 py-10 text-center">
                <div class="flex justify-center mb-4">
                    <span class="material-symbols-outlined text-white text-6xl">verified_user</span>
                </div>
                <h1 class="text-white text-2xl font-bold mb-2">LSP-PIE Admin</h1>
                <p class="text-blue-200 text-sm">Lembaga Sertifikasi Profesi<br>Pustaka Ilmiah Elektronik</p>
            </div>

            <!-- Login Form -->
            <div class="px-8 py-10">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
                            <div>
                                <h3 class="text-red-800 font-semibold text-sm mb-1">Login Gagal</h3>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-700 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">email</span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="admin@lsp-pie.ac.id"
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full h-12 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-lg transition duration-300 flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined">login</span>
                        <span>Masuk ke Dashboard</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <a href="/" class="flex items-center justify-center gap-2 text-sm text-gray-600 hover:text-blue-900 transition">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <p class="text-center text-sm text-gray-600 mt-6">
            © 2024 LSP Pustaka Ilmiah Elektronik
        </p>
    </div>
</body>
</html>
