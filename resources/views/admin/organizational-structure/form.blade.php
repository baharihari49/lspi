<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content - Left Column (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Info Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $position->name ?? '') }}"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                        placeholder="Nama lengkap"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan *</label>
                    <input
                        type="text"
                        id="position"
                        name="position"
                        value="{{ old('position', $position->position ?? '') }}"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('position') border-red-500 @enderror"
                        placeholder="Contoh: Direktur Utama, Ketua"
                    >
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $position->email ?? '') }}"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror"
                        placeholder="email@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $position->phone ?? '') }}"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('phone') border-red-500 @enderror"
                        placeholder="08xx-xxxx-xxxx"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Photo Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Foto Profil</h3>

            @if($position && $position->photo)
                <div class="mb-4">
                    <img src="{{ Storage::url($position->photo) }}" alt="{{ $position->name }}" class="w-32 h-32 rounded-lg object-cover border border-gray-200">
                </div>
            @endif

            <div>
                <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">Upload Foto</label>
                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept="image/jpeg,image/jpg,image/png"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('photo') border-red-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                @error('photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Sidebar - Right Column (1/3 width) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Organizational Info Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Organisasi</h3>
            <div class="space-y-4">
                <!-- Level -->
                <div>
                    <label for="level" class="block text-sm font-semibold text-gray-700 mb-2">Level *</label>
                    <select
                        id="level"
                        name="level"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('level') border-red-500 @enderror"
                    >
                        <option value="">Pilih Level</option>
                        <option value="Ketua" {{ old('level', $position->level ?? '') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                        <option value="Wakil Ketua" {{ old('level', $position->level ?? '') == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                        <option value="Sekretaris" {{ old('level', $position->level ?? '') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ old('level', $position->level ?? '') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="Koordinator" {{ old('level', $position->level ?? '') == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
                        <option value="Anggota" {{ old('level', $position->level ?? '') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Position -->
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-2">Atasan Langsung</label>
                    <select
                        id="parent_id"
                        name="parent_id"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('parent_id') border-red-500 @enderror"
                    >
                        <option value="">Tidak Ada (Root Level)</option>
                        @foreach($allPositions as $pos)
                            <option value="{{ $pos->id }}" {{ old('parent_id', $position->parent_id ?? '') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->name }} - {{ $pos->position }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampilan *</label>
                    <input
                        type="number"
                        id="order"
                        name="order"
                        value="{{ old('order', $position->order ?? 0) }}"
                        required
                        min="0"
                        class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('order') border-red-500 @enderror"
                        placeholder="0"
                    >
                    <p class="mt-1 text-xs text-gray-500">Angka lebih kecil akan ditampilkan lebih dulu</p>
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    {{ old('is_active', $position->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="is_active" class="ml-2 text-sm text-gray-700">
                    Posisi aktif (akan ditampilkan di website)
                </label>
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
                    <span>{{ $position ? 'Perbarui' : 'Simpan' }}</span>
                </button>
                <a
                    href="{{ route('admin.organizational-structure.index') }}"
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition"
                >
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </div>
</div>
