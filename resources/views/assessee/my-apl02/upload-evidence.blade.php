@extends('layouts.admin')

@section('title', 'Upload Bukti')

@php $active = 'my-apl02'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.my-apl02.index') }}" class="hover:text-blue-600">APL-02 Saya</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.my-apl02.show', $unit) }}" class="hover:text-blue-600">{{ $unit->unit_code }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span>Upload Bukti</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Upload Bukti Kompetensi</h1>
        <p class="text-gray-600 mt-1">{{ $unit->unit_title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.my-apl02.store-evidence', $unit) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <!-- Evidence Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bukti <span class="text-red-500">*</span></label>
                            <select name="evidence_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('evidence_type') border-red-500 @enderror">
                                <option value="">Pilih Jenis Bukti</option>
                                <option value="document" {{ old('evidence_type') === 'document' ? 'selected' : '' }}>Dokumen</option>
                                <option value="certificate" {{ old('evidence_type') === 'certificate' ? 'selected' : '' }}>Sertifikat</option>
                                <option value="work_sample" {{ old('evidence_type') === 'work_sample' ? 'selected' : '' }}>Contoh Hasil Kerja</option>
                                <option value="project" {{ old('evidence_type') === 'project' ? 'selected' : '' }}>Proyek</option>
                                <option value="photo" {{ old('evidence_type') === 'photo' ? 'selected' : '' }}>Foto</option>
                                <option value="video" {{ old('evidence_type') === 'video' ? 'selected' : '' }}>Video</option>
                                <option value="portfolio" {{ old('evidence_type') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                            </select>
                            @error('evidence_type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Bukti <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                placeholder="Contoh: Sertifikat Pelatihan Python 2024"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3"
                                placeholder="Jelaskan bagaimana bukti ini menunjukkan kompetensi Anda..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                                <input type="file" name="file" id="file" required
                                    class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4,.zip">
                                <label for="file" class="cursor-pointer">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">cloud_upload</span>
                                    <p class="mt-2 text-gray-600">Klik untuk memilih file atau drag & drop</p>
                                    <p class="text-sm text-gray-400 mt-1">PDF, DOC, JPG, PNG, MP4, ZIP (Max 10MB)</p>
                                </label>
                                <p id="file-name" class="mt-2 text-sm text-blue-600 font-medium"></p>
                            </div>
                            @error('file')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.my-apl02.show', $unit) }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                Upload Bukti
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-xl p-6">
                <h3 class="font-semibold text-blue-900 mb-3">Tips Upload Bukti</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Pastikan dokumen jelas dan terbaca
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Sertifikat harus mencantumkan nama lengkap Anda
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Foto hasil kerja harus menunjukkan detail yang relevan
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                        Berikan deskripsi yang jelas untuk setiap bukti
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Jenis Bukti yang Diterima</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><strong>Dokumen:</strong> CV, ijazah, transkrip</li>
                    <li><strong>Sertifikat:</strong> Sertifikat pelatihan, kursus</li>
                    <li><strong>Hasil Kerja:</strong> Laporan, proposal, dokumen kerja</li>
                    <li><strong>Foto:</strong> Dokumentasi kegiatan</li>
                    <li><strong>Video:</strong> Rekaman demonstrasi</li>
                    <li><strong>Portfolio:</strong> Kumpulan karya</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('file-name').textContent = fileName;
});
</script>
@endpush
@endsection
