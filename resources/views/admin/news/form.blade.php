<div class="space-y-6">
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
        <textarea
            id="content"
            name="content"
            required
            rows="12"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-y @error('content') border-red-500 @enderror"
            placeholder="Tulis konten berita lengkap di sini..."
        >{{ old('content', $news->content ?? '') }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Category, Icon, Icon Color -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Category -->
            <div>
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
                    class="w-full md:w-1/2 h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('published_at') border-red-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan waktu saat ini</p>
                @error('published_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <button
            type="submit"
            class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition"
        >
            <span class="material-symbols-outlined">save</span>
            <span>{{ $news ? 'Perbarui Berita' : 'Simpan Berita' }}</span>
        </button>
        <a
            href="{{ route('admin.news.index') }}"
            class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition"
        >
            <span class="material-symbols-outlined">cancel</span>
            <span>Batal</span>
        </a>
    </div>
</div>
