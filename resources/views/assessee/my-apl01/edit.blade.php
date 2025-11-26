@extends('layouts.admin')

@section('title', 'Edit APL-01 - ' . $apl01->form_number)

@php
    $active = 'my-apl01';
@endphp

@section('page_title', 'Edit APL-01')
@section('page_description', $apl01->form_number)

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

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
                <span class="material-symbols-outlined text-red-600 mr-3">error</span>
                <div>
                    <p class="text-red-800 font-medium">Terdapat kesalahan pada formulir:</p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.my-apl01.update', $apl01) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Form Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        Informasi Formulir
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Form Number (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Formulir</label>
                            <input type="text" value="{{ $apl01->form_number }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-gray-50 text-gray-600">
                        </div>

                        <!-- Scheme (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Skema Sertifikasi</label>
                            <input type="text" value="{{ $apl01->scheme->name ?? '-' }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-gray-50 text-gray-600">
                        </div>

                        <!-- Event (Read-only) -->
                        @if($apl01->event)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event</label>
                            <input type="text" value="{{ $apl01->event->name }} ({{ $apl01->event->start_date?->format('d M Y') }})" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-gray-50 text-gray-600">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">person</span>
                        Data Pribadi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $apl01->full_name) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('full_name') border-red-500 @enderror">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div>
                            <label for="id_number" class="block text-sm font-semibold text-gray-700 mb-2">NIK/Nomor Identitas *</label>
                            <input type="text" id="id_number" name="id_number" value="{{ old('id_number', $apl01->id_number) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('id_number') border-red-500 @enderror">
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $apl01->date_of_birth?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place of Birth -->
                        <div>
                            <label for="place_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth', $apl01->place_of_birth) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('place_of_birth') border-red-500 @enderror">
                            @error('place_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select id="gender" name="gender"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gender') border-red-500 @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender', $apl01->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $apl01->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">Kewarganegaraan</label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $apl01->nationality ?? 'Indonesia') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('nationality') border-red-500 @enderror">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $apl01->email) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">No. HP *</label>
                            <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $apl01->mobile) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('mobile') border-red-500 @enderror">
                            @error('mobile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Telepon (Opsional)</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $apl01->phone) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('address') border-red-500 @enderror">{{ old('address', $apl01->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                            <input type="text" id="city" name="city" value="{{ old('city', $apl01->city) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                            <input type="text" id="province" name="province" value="{{ old('province', $apl01->province) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('province') border-red-500 @enderror">
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $apl01->postal_code) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('postal_code') border-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">business</span>
                        Informasi Pekerjaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Current Company -->
                        <div class="md:col-span-2">
                            <label for="current_company" class="block text-sm font-semibold text-gray-700 mb-2">Perusahaan/Instansi Saat Ini</label>
                            <input type="text" id="current_company" name="current_company" value="{{ old('current_company', $apl01->current_company) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_company') border-red-500 @enderror">
                            @error('current_company')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Position -->
                        <div>
                            <label for="current_position" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan Saat Ini</label>
                            <input type="text" id="current_position" name="current_position" value="{{ old('current_position', $apl01->current_position) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_position') border-red-500 @enderror">
                            @error('current_position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Industry -->
                        <div>
                            <label for="current_industry" class="block text-sm font-semibold text-gray-700 mb-2">Bidang Industri</label>
                            <input type="text" id="current_industry" name="current_industry" value="{{ old('current_industry', $apl01->current_industry) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('current_industry') border-red-500 @enderror">
                            @error('current_industry')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certification Purpose -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        Informasi Sertifikasi
                    </h3>

                    <div class="space-y-4">
                        <!-- Certification Purpose -->
                        <div>
                            <label for="certification_purpose" class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Sertifikasi</label>
                            <textarea id="certification_purpose" name="certification_purpose" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certification_purpose') border-red-500 @enderror">{{ old('certification_purpose', $apl01->certification_purpose) }}</textarea>
                            @error('certification_purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Competency -->
                        <div>
                            <label for="target_competency" class="block text-sm font-semibold text-gray-700 mb-2">Target Kompetensi</label>
                            <textarea id="target_competency" name="target_competency" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('target_competency') border-red-500 @enderror">{{ old('target_competency', $apl01->target_competency) }}</textarea>
                            @error('target_competency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">gavel</span>
                        Pernyataan
                    </h3>

                    <div class="p-4 bg-gray-50 rounded-lg mb-4">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            Dengan ini saya menyatakan bahwa:
                        </p>
                        <ul class="mt-2 text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Semua data yang saya berikan adalah benar dan dapat dipertanggungjawabkan</li>
                            <li>Saya bersedia mengikuti seluruh proses sertifikasi sesuai dengan ketentuan yang berlaku</li>
                            <li>Saya bersedia menerima sanksi sesuai ketentuan jika melanggar peraturan yang berlaku</li>
                        </ul>
                    </div>

                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="declaration_agreed" name="declaration_agreed" value="1"
                            {{ old('declaration_agreed', $apl01->declaration_agreed) ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="declaration_agreed" class="text-sm text-gray-700">
                            Saya menyetujui pernyataan di atas dan bertanggung jawab atas kebenaran data yang saya berikan
                        </label>
                    </div>
                    @error('declaration_agreed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column: Actions & Status -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>

                    <div class="space-y-3">
                        <button type="submit" name="action" value="save" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Simpan Perubahan</span>
                        </button>

                        @if(in_array($apl01->status, ['draft', 'revised']))
                            <button type="submit" name="action" value="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">send</span>
                                <span>Simpan & Submit</span>
                            </button>
                        @endif

                        <a href="{{ route('admin.my-apl01.show', $apl01) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Batal</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Catatan:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>* = wajib diisi</li>
                            <li>Formulir hanya dapat diedit jika status Draft atau Revisi</li>
                            <li>Pastikan semua data sudah benar sebelum submit</li>
                        </ul>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status Formulir</h3>

                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'submitted' => 'bg-yellow-100 text-yellow-800',
                            'under_review' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'revised' => 'bg-orange-100 text-orange-800',
                        ];
                        $statusLabels = [
                            'draft' => 'Draft',
                            'submitted' => 'Submitted',
                            'under_review' => 'Under Review',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'revised' => 'Perlu Revisi',
                        ];
                    @endphp

                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$apl01->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$apl01->status] ?? $apl01->status }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2">
                        <p><span class="font-medium">Dibuat:</span> {{ $apl01->created_at?->format('d M Y H:i') }}</p>
                        <p><span class="font-medium">Terakhir diupdate:</span> {{ $apl01->updated_at?->format('d M Y H:i') }}</p>
                        @if($apl01->submitted_at)
                            <p><span class="font-medium">Disubmit:</span> {{ $apl01->submitted_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>

                    @if($apl01->status === 'rejected' && $apl01->rejection_reason)
                        <div class="mt-4 p-3 bg-red-50 rounded-lg">
                            <p class="text-xs font-semibold text-red-800 mb-1">Alasan Penolakan:</p>
                            <p class="text-sm text-red-700">{{ $apl01->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($apl01->admin_notes)
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                            <p class="text-xs font-semibold text-yellow-800 mb-1">Catatan Admin:</p>
                            <p class="text-sm text-yellow-700">{{ $apl01->admin_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Scheme Info Card -->
                @if($apl01->scheme)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Skema Sertifikasi</h3>

                    <div class="text-sm space-y-2">
                        <p class="font-bold text-blue-900">{{ $apl01->scheme->name }}</p>
                        <p class="text-gray-600">Kode: {{ $apl01->scheme->code }}</p>
                        @if($apl01->scheme->description)
                            <p class="text-gray-500 text-xs mt-2">{{ Str::limit($apl01->scheme->description, 150) }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
@endsection
