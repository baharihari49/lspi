@extends('layouts.admin')

@section('title', 'Add Event')

@php
    $active = 'events';
@endphp

@section('page_title', 'Add Event')
@section('page_description', 'Create new certification or training event')

@section('content')
    <form action="{{ route('admin.events.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar</h3>

                    <div class="space-y-4">
                        <!-- Code (Auto-generated) -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Kode Event</label>
                            <input type="text" id="code" value="{{ $nextCode }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed outline-none font-mono text-gray-600">
                            <p class="mt-1 text-xs text-gray-500">
                                <span class="material-symbols-outlined text-xs align-middle">auto_awesome</span>
                                Kode akan di-generate otomatis saat event disimpan
                            </p>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Event *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                                placeholder="e.g., Sertifikasi Pengelolaan Jurnal Elektronik - Batch 1">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label for="event_type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Event *</label>
                            <select id="event_type" name="event_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_type') border-red-500 @enderror">
                                <option value="">Pilih Tipe</option>
                                <option value="certification" {{ old('event_type') == 'certification' ? 'selected' : '' }}>Sertifikasi</option>
                                <option value="training" {{ old('event_type') == 'training' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('event_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Deskripsi event...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule & Dates -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Jadwal & Tanggal</h3>

                    <div class="space-y-4">
                        <!-- Event Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai *</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai *</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Harus setelah tanggal mulai</p>
                            </div>
                        </div>

                        <!-- Registration Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="registration_start" class="block text-sm font-semibold text-gray-700 mb-2">Registrasi Mulai</label>
                                <input type="date" id="registration_start" name="registration_start" value="{{ old('registration_start') }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('registration_start') border-red-500 @enderror">
                                @error('registration_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="registration_end" class="block text-sm font-semibold text-gray-700 mb-2">Registrasi Selesai</label>
                                <input type="date" id="registration_end" name="registration_end" value="{{ old('registration_end') }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('registration_end') border-red-500 @enderror">
                                @error('registration_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants & Fees -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Peserta & Biaya</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Scheme -->
                            <div>
                                <label for="scheme_id" class="block text-sm font-semibold text-gray-700 mb-2">Skema Sertifikasi</label>
                                <select id="scheme_id" name="scheme_id"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_id') border-red-500 @enderror">
                                    <option value="">Tidak Ada</option>
                                    @foreach(\App\Models\Scheme::orderBy('name')->get() as $scheme)
                                        <option value="{{ $scheme->id }}" {{ old('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                            {{ $scheme->code }} - {{ $scheme->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('scheme_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status Event *</label>
                                <select id="status_id" name="status_id" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                    <option value="">Pilih Status</option>
                                    @foreach(\App\Models\MasterStatus::where('category', 'event')->orderBy('sort_order')->get() as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Draft, Published, Ongoing, Completed, Cancelled</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Max Participants -->
                            <div>
                                <label for="max_participants" class="block text-sm font-semibold text-gray-700 mb-2">Maks Peserta</label>
                                <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('max_participants') border-red-500 @enderror"
                                    placeholder="Kosongkan untuk tidak terbatas">
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Registration Fee -->
                            <div>
                                <label for="registration_fee" class="block text-sm font-semibold text-gray-700 mb-2">Biaya Pendaftaran (Rp)</label>
                                <input type="number" id="registration_fee" name="registration_fee" value="{{ old('registration_fee') }}" min="0" step="1000"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('registration_fee') border-red-500 @enderror"
                                    placeholder="0">
                                @error('registration_fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Lokasi</h3>

                    <div class="space-y-4">
                        <!-- Location Name -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror"
                                placeholder="Cth: Jakarta, Bandung, Online">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Address -->
                        <div>
                            <label for="location_address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lokasi</label>
                            <textarea id="location_address" name="location_address" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location_address') border-red-500 @enderror"
                                placeholder="Alamat lengkap lokasi event...">{{ old('location_address') }}</textarea>
                            @error('location_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Internal</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Catatan internal (tidak terlihat peserta)...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkbox -->
                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Aktifkan</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-8">Event aktif dan menerima pendaftaran</p>
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
                                <h4 class="font-semibold text-blue-900 mb-2">Panduan Event</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Isi semua field wajib (*)</li>
                                    <li>• Kode event otomatis di-generate</li>
                                    <li>• Atur tanggal event dan registrasi</li>
                                    <li>• Konfigurasi batas peserta</li>
                                    <li>• Tambahkan detail lokasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Buat Event</span>
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Batal</span>
                            </a>
                        </div>
                    </div>

                    <!-- Type Guide -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Panduan Tipe Event</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Sertifikasi:</span>
                                    <span class="block text-xs">Event sertifikasi kompetensi resmi</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Pelatihan:</span>
                                    <span class="block text-xs">Program pengembangan keterampilan</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Workshop:</span>
                                    <span class="block text-xs">Sesi praktik langsung</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-3 h-3 rounded-full bg-gray-500 mt-1 flex-shrink-0"></span>
                                <div>
                                    <span class="font-semibold text-gray-900">Lainnya:</span>
                                    <span class="block text-xs">Tipe event lainnya</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
