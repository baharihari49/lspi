@extends('layouts.app')

@section('title', 'Hubungi Kami - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'contact';
@endphp

@section('content')
    <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-12 md:py-16">
        <div class="flex flex-col max-w-[1200px] w-full">
            <!-- Page Heading -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-3">Hubungi Kami</h1>
                <p class="text-lg text-gray-600">
                    Kami siap membantu Anda. Silakan hubungi kami melalui detail di bawah ini atau kirimkan pesan melalui formulir.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Contact Info -->
                <div class="flex flex-col gap-6">
                    <h2 class="text-2xl font-bold text-blue-900">LSP Pihak Ketiga</h2>
                    <p class="text-lg text-gray-700 -mt-2">LSP Pustaka Ilmiah Elektronik</p>

                    <div class="flex flex-col gap-6 mt-4">
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                <span class="material-symbols-outlined text-blue-900">mail</span>
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-base font-semibold text-gray-900">Email</p>
                                <p class="text-sm text-gray-600">lsp@relawanjurnal.id</p>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                <span class="material-symbols-outlined text-blue-900">phone</span>
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-base font-semibold text-gray-900">Telepon</p>
                                <p class="text-sm text-gray-600">081339595151</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                <span class="material-symbols-outlined text-blue-900">location_on</span>
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-base font-semibold text-gray-900">Alamat</p>
                                <p class="text-sm text-gray-600">Jalan Raya Bibis, Ngentak, Bangunjiwo, Kel. Tamantirto, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact Form -->
                <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg border border-gray-200">
                    <h2 class="text-xl font-bold text-blue-900 mb-6">Kirimkan Pesan Anda</h2>
                    <form action="#" method="POST" class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" placeholder="contoh@email.com" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>

                        <div>
                            <label for="subjek" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                            <input type="text" id="subjek" name="subjek" placeholder="Tuliskan subjek pesan Anda" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>

                        <div>
                            <label for="pesan" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="pesan" name="pesan" rows="4" placeholder="Tuliskan pesan Anda di sini..." class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-bold text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300">
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Lokasi Kami</h2>
                <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2887894651746!2d110.32841531477736!3d-7.838397994379047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5788b8c4c3f3%3A0x5c6a6e9e8b9e4c8c!2sJl.%20Raya%20Bibis%2C%20Ngentak%2C%20Bangunjiwo%2C%20Kec.%20Kasihan%2C%20Kabupaten%20Bantul%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
