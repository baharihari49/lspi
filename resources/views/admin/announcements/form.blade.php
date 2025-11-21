<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content - Left Column (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Title -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Pengumuman *</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $announcement->title ?? '') }}"
                required
                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none @error('title') border-red-500 @enderror"
                placeholder="Masukkan judul pengumuman..."
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Excerpt -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">Ringkasan *</label>
            <textarea
                id="excerpt"
                name="excerpt"
                required
                rows="3"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none @error('excerpt') border-red-500 @enderror"
                placeholder="Ringkasan singkat pengumuman..."
            >{{ old('excerpt', $announcement->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Konten Lengkap *</label>
            <input id="content" type="hidden" name="content" value="{{ old('content', $announcement->content ?? '') }}" required>
            <trix-editor input="content" class="@error('content') border-red-500 @enderror" placeholder="Tulis konten pengumuman lengkap di sini..."></trix-editor>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Sidebar - Right Column (1/3 width) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
            <select
                id="category"
                name="category"
                required
                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none @error('category') border-red-500 @enderror"
            >
                <option value="">Pilih Kategori</option>
                <option value="Pengumuman Resmi" {{ old('category', $announcement->category ?? '') == 'Pengumuman Resmi' ? 'selected' : '' }}>Pengumuman Resmi</option>
                <option value="Jadwal Ujian" {{ old('category', $announcement->category ?? '') == 'Jadwal Ujian' ? 'selected' : '' }}>Jadwal Ujian</option>
                <option value="Sertifikasi" {{ old('category', $announcement->category ?? '') == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                <option value="Peraturan" {{ old('category', $announcement->category ?? '') == 'Peraturan' ? 'selected' : '' }}>Peraturan</option>
            </select>
            @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Publishing Options -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Opsi Publikasi</h3>
            <div class="space-y-4">
                <!-- Published Checkbox -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="is_published"
                        name="is_published"
                        value="1"
                        {{ old('is_published', $announcement->is_published ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                    >
                    <label for="is_published" class="ml-2 text-sm text-gray-700">
                        Publikasikan pengumuman ini
                    </label>
                </div>

                <!-- Publish Date -->
                <div>
                    <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input
                        type="datetime-local"
                        id="published_at"
                        name="published_at"
                        value="{{ old('published_at', $announcement?->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none @error('published_at') border-red-500 @enderror"
                    >
                    <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan waktu saat ini</p>
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="space-y-3">
                <button
                    type="submit"
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition"
                >
                    <span class="material-symbols-outlined">save</span>
                    <span>{{ $announcement ? 'Perbarui' : 'Simpan' }}</span>
                </button>
                <a
                    href="{{ route('admin.announcements.index') }}"
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition"
                >
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </div>
</div>
