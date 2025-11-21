<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content - Left Column (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Title -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita *</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $news->title ?? '') }}"
                required
                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror"
                placeholder="Masukkan judul berita..."
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured Image -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Utama</label>

            <!-- Current Image (only show if editing and has image) -->
            @if($news && $news->image)
                <div id="currentImage" class="mb-4">
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    <p class="mt-2 text-xs text-gray-500">Gambar saat ini (upload gambar baru untuk menggantinya)</p>
                </div>
            @endif

            <!-- Preview Container (hidden by default) -->
            <div id="imagePreviewContainer" class="mb-4 hidden">
                <div class="relative">
                    <img id="imagePreview" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg border-2 border-blue-500">
                    <button
                        type="button"
                        onclick="removeImagePreview()"
                        class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition"
                        title="Hapus preview"
                    >
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                <p class="mt-2 text-xs text-green-600 font-semibold">✓ Preview gambar baru (belum tersimpan)</p>
            </div>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/jpg,image/png,image/webp"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('image') border-red-500 @enderror"
                onchange="previewImage(event)"
            >
            <p class="mt-2 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB. Rekomendasi: 1200x630px</p>
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('imagePreviewContainer');
                const previewImg = document.getElementById('imagePreview');
                const currentImage = document.getElementById('currentImage');

                if (file) {
                    // Validate file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 5MB.');
                        event.target.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak valid! Gunakan JPG, PNG, atau WEBP.');
                        event.target.value = '';
                        return;
                    }

                    // Read and display preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');

                        // Hide current image when showing preview
                        if (currentImage) {
                            currentImage.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    // If no file selected, hide preview and show current image again
                    previewContainer.classList.add('hidden');
                    if (currentImage) {
                        currentImage.classList.remove('hidden');
                    }
                }
            }

            function removeImagePreview() {
                const fileInput = document.getElementById('image');
                const previewContainer = document.getElementById('imagePreviewContainer');
                const currentImage = document.getElementById('currentImage');

                // Clear file input
                fileInput.value = '';

                // Hide preview
                previewContainer.classList.add('hidden');

                // Show current image again if exists
                if (currentImage) {
                    currentImage.classList.remove('hidden');
                }
            }
        </script>

        <!-- Excerpt -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">Ringkasan *</label>
            <textarea
                id="excerpt"
                name="excerpt"
                required
                rows="3"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('excerpt') border-red-500 @enderror"
                placeholder="Ringkasan singkat berita..."
            >{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Konten Lengkap *</label>
            <input id="content" type="hidden" name="content" value="{{ old('content', $news->content ?? '') }}" required>
            <trix-editor input="content" class="@error('content') border-red-500 @enderror" placeholder="Tulis konten berita lengkap di sini..."></trix-editor>
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
                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('category') border-red-500 @enderror"
            >
                <option value="">Pilih Kategori</option>
                <option value="Artikel" {{ old('category', $news->category ?? '') == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                <option value="Tips & Trik" {{ old('category', $news->category ?? '') == 'Tips & Trik' ? 'selected' : '' }}>Tips & Trik</option>
                <option value="Industri" {{ old('category', $news->category ?? '') == 'Industri' ? 'selected' : '' }}>Industri</option>
            </select>
            @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Icon Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Pengaturan Ikon</h3>
            <div class="space-y-4">
                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Ikon *</label>
                    <select
                        id="icon"
                        name="icon"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('icon') border-red-500 @enderror"
                    >
                        <option value="article" {{ old('icon', $news->icon ?? 'article') == 'article' ? 'selected' : '' }}>article</option>
                        <option value="lightbulb" {{ old('icon', $news->icon ?? '') == 'lightbulb' ? 'selected' : '' }}>lightbulb</option>
                        <option value="trending_up" {{ old('icon', $news->icon ?? '') == 'trending_up' ? 'selected' : '' }}>trending_up</option>
                        <option value="menu_book" {{ old('icon', $news->icon ?? '') == 'menu_book' ? 'selected' : '' }}>menu_book</option>
                        <option value="school" {{ old('icon', $news->icon ?? '') == 'school' ? 'selected' : '' }}>school</option>
                        <option value="workspace_premium" {{ old('icon', $news->icon ?? '') == 'workspace_premium' ? 'selected' : '' }}>workspace_premium</option>
                    </select>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon Color -->
                <div>
                    <label for="icon_color" class="block text-sm font-semibold text-gray-700 mb-2">Warna Ikon *</label>
                    <select
                        id="icon_color"
                        name="icon_color"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('icon_color') border-red-500 @enderror"
                    >
                        <option value="blue" {{ old('icon_color', $news->icon_color ?? 'blue') == 'blue' ? 'selected' : '' }}>Blue</option>
                        <option value="green" {{ old('icon_color', $news->icon_color ?? '') == 'green' ? 'selected' : '' }}>Green</option>
                        <option value="purple" {{ old('icon_color', $news->icon_color ?? '') == 'purple' ? 'selected' : '' }}>Purple</option>
                        <option value="orange" {{ old('icon_color', $news->icon_color ?? '') == 'orange' ? 'selected' : '' }}>Orange</option>
                        <option value="red" {{ old('icon_color', $news->icon_color ?? '') == 'red' ? 'selected' : '' }}>Red</option>
                        <option value="teal" {{ old('icon_color', $news->icon_color ?? '') == 'teal' ? 'selected' : '' }}>Teal</option>
                    </select>
                    @error('icon_color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
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
                        {{ old('is_published', $news->is_published ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <label for="is_published" class="ml-2 text-sm text-gray-700">
                        Publikasikan berita ini
                    </label>
                </div>

                <!-- Publish Date -->
                <div>
                    <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input
                        type="datetime-local"
                        id="published_at"
                        name="published_at"
                        value="{{ old('published_at', $news?->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('published_at') border-red-500 @enderror"
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
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition"
                >
                    <span class="material-symbols-outlined">save</span>
                    <span>{{ $news ? 'Perbarui' : 'Simpan' }}</span>
                </button>
                <a
                    href="{{ route('admin.news.index') }}"
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition"
                >
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </div>
</div>
