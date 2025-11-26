@extends('layouts.admin')

@section('title', 'Upload Bukti')

@php $active = 'my-apl02'; @endphp

@section('page_title', 'Upload Bukti')
@section('page_description', $unit->unit_code . ' - ' . $unit->unit_title)

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.my-apl02.store-evidence', $unit) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
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

                        <!-- Upload Mode Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mode Upload</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="upload_mode" value="file" checked class="text-blue-600" onchange="toggleUploadMode('file')">
                                    <span class="text-sm text-gray-700">File Tunggal</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="upload_mode" value="multiple" class="text-blue-600" onchange="toggleUploadMode('multiple')">
                                    <span class="text-sm text-gray-700">Multiple Files</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="upload_mode" value="folder" class="text-blue-600" onchange="toggleUploadMode('folder')">
                                    <span class="text-sm text-gray-700">Folder</span>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload - Single -->
                        <div id="single-upload">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition" id="drop-zone-single">
                                <input type="file" name="file" id="file-single"
                                    class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.zip,.rar">
                                <label for="file-single" class="cursor-pointer">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">cloud_upload</span>
                                    <p class="mt-2 text-gray-600">Klik untuk memilih file atau drag & drop</p>
                                    <p class="text-sm text-gray-400 mt-1">PDF, DOC, JPG, PNG, MP4, ZIP, RAR (Max 10MB)</p>
                                </label>
                                <p id="file-name-single" class="mt-2 text-sm text-blue-600 font-medium"></p>
                            </div>
                        </div>

                        <!-- File Upload - Multiple -->
                        <div id="multiple-upload" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Files <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition" id="drop-zone-multiple">
                                <input type="file" name="files[]" id="file-multiple" multiple
                                    class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.zip,.rar">
                                <label for="file-multiple" class="cursor-pointer" id="multiple-label">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">cloud_upload</span>
                                    <p class="mt-2 text-gray-600">Klik untuk memilih beberapa file</p>
                                    <p class="text-sm text-gray-400 mt-1">PDF, DOC, JPG, PNG, MP4, ZIP, RAR (Max 10MB per file)</p>
                                </label>
                            </div>
                            <!-- Multiple Files Preview -->
                            <div id="multiple-preview" class="hidden mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-500">attach_file</span>
                                        <span class="font-medium text-gray-900">Selected Files</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span id="multiple-file-count"></span>
                                        <span id="multiple-total-size"></span>
                                        <button type="button" onclick="clearMultipleSelection()" class="text-red-500 hover:text-red-700">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                    </div>
                                </div>
                                <div id="multiple-file-list" class="max-h-64 overflow-y-auto divide-y divide-gray-100"></div>
                            </div>
                        </div>

                        <!-- File Upload - Folder -->
                        <div id="folder-upload" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Folder <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition" id="drop-zone-folder">
                                <input type="file" name="files[]" id="file-folder" webkitdirectory multiple
                                    class="hidden">
                                <label for="file-folder" class="cursor-pointer" id="folder-label">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">folder_open</span>
                                    <p class="mt-2 text-gray-600">Klik untuk memilih folder</p>
                                    <p class="text-sm text-gray-400 mt-1">Semua file dalam folder akan diupload</p>
                                </label>
                            </div>
                            <!-- Folder Preview -->
                            <div id="folder-preview" class="hidden mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-yellow-500">folder</span>
                                        <span id="folder-name" class="font-medium text-gray-900"></span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span id="folder-file-count"></span>
                                        <span id="folder-total-size"></span>
                                        <button type="button" onclick="clearFolderSelection()" class="text-red-500 hover:text-red-700">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                    </div>
                                </div>
                                <div id="folder-file-list" class="max-h-64 overflow-y-auto divide-y divide-gray-100"></div>
                            </div>
                        </div>

                        @error('file')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @error('files')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @error('files.*')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.my-apl02.show', $unit) }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="px-6 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition flex items-center gap-2">
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

            <div class="bg-yellow-50 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-900 mb-3">Mode Upload</h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li><strong>File Tunggal:</strong> Upload satu file saja</li>
                    <li><strong>Multiple Files:</strong> Pilih beberapa file sekaligus</li>
                    <li><strong>Folder:</strong> Upload semua file dalam satu folder</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
<script>
let currentMode = 'file';

function toggleUploadMode(mode) {
    currentMode = mode;

    // Hide all upload sections
    document.getElementById('single-upload').classList.add('hidden');
    document.getElementById('multiple-upload').classList.add('hidden');
    document.getElementById('folder-upload').classList.add('hidden');

    // Disable all file inputs
    document.getElementById('file-single').disabled = true;
    document.getElementById('file-single').removeAttribute('required');
    document.getElementById('file-multiple').disabled = true;
    document.getElementById('file-folder').disabled = true;

    // Show and enable selected mode
    if (mode === 'file') {
        document.getElementById('single-upload').classList.remove('hidden');
        document.getElementById('file-single').disabled = false;
        document.getElementById('file-single').setAttribute('required', 'required');
    } else if (mode === 'multiple') {
        document.getElementById('multiple-upload').classList.remove('hidden');
        document.getElementById('file-multiple').disabled = false;
    } else if (mode === 'folder') {
        document.getElementById('folder-upload').classList.remove('hidden');
        document.getElementById('file-folder').disabled = false;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    toggleUploadMode('file');
});

// Single file
document.getElementById('file-single').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('file-name-single').textContent = fileName;
});

// Multiple files
document.getElementById('file-multiple').addEventListener('change', function(e) {
    const preview = document.getElementById('multiple-preview');
    const fileList = document.getElementById('multiple-file-list');
    const multipleLabel = document.getElementById('multiple-label');

    if (e.target.files.length > 0) {
        const files = Array.from(e.target.files);
        const count = files.length;

        // Calculate total size
        const totalSize = files.reduce((acc, file) => acc + file.size, 0);

        // Update header info
        document.getElementById('multiple-file-count').textContent = `${count} file(s)`;
        document.getElementById('multiple-total-size').textContent = formatFileSize(totalSize);

        // Build file list
        fileList.innerHTML = '';
        files.forEach((file) => {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 px-4 py-2 hover:bg-gray-50';
            row.innerHTML = `
                <span class="material-symbols-outlined text-gray-400 text-lg flex-shrink-0">${getFileIcon(file.type, file.name)}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900 truncate">${file.name}</p>
                </div>
                <span class="text-xs text-gray-500 flex-shrink-0">${formatFileSize(file.size)}</span>
            `;
            fileList.appendChild(row);
        });

        // Show preview, hide label
        preview.classList.remove('hidden');
        multipleLabel.classList.add('hidden');
    } else {
        preview.classList.add('hidden');
        multipleLabel.classList.remove('hidden');
    }
});

function clearMultipleSelection() {
    document.getElementById('file-multiple').value = '';
    document.getElementById('multiple-preview').classList.add('hidden');
    document.getElementById('multiple-label').classList.remove('hidden');
}

// Folder
document.getElementById('file-folder').addEventListener('change', function(e) {
    const preview = document.getElementById('folder-preview');
    const fileList = document.getElementById('folder-file-list');
    const folderLabel = document.getElementById('folder-label');

    if (e.target.files.length > 0) {
        const files = Array.from(e.target.files);
        const count = files.length;

        // Get folder name from first file's path
        const firstPath = files[0].webkitRelativePath || '';
        const folderName = firstPath.split('/')[0] || 'Selected Folder';

        // Calculate total size
        const totalSize = files.reduce((acc, file) => acc + file.size, 0);

        // Update header info
        document.getElementById('folder-name').textContent = folderName;
        document.getElementById('folder-file-count').textContent = `${count} file(s)`;
        document.getElementById('folder-total-size').textContent = formatFileSize(totalSize);

        // Build file list
        fileList.innerHTML = '';
        files.forEach((file, index) => {
            const relativePath = file.webkitRelativePath || file.name;
            const fileName = relativePath.split('/').pop();
            const filePath = relativePath.split('/').slice(1, -1).join('/');

            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 px-4 py-2 hover:bg-gray-50';
            row.innerHTML = `
                <span class="material-symbols-outlined text-gray-400 text-lg flex-shrink-0">${getFileIcon(file.type, fileName)}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900 truncate">${fileName}</p>
                    ${filePath ? `<p class="text-xs text-gray-500 truncate">${filePath}</p>` : ''}
                </div>
                <span class="text-xs text-gray-500 flex-shrink-0">${formatFileSize(file.size)}</span>
            `;
            fileList.appendChild(row);
        });

        // Show preview, hide label
        preview.classList.remove('hidden');
        folderLabel.classList.add('hidden');
    } else {
        preview.classList.add('hidden');
        folderLabel.classList.remove('hidden');
    }
});

function clearFolderSelection() {
    document.getElementById('file-folder').value = '';
    document.getElementById('folder-preview').classList.add('hidden');
    document.getElementById('folder-label').classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function getFileIcon(mimeType, fileName) {
    const ext = fileName.split('.').pop().toLowerCase();

    // By extension
    const extIcons = {
        'pdf': 'picture_as_pdf',
        'doc': 'description',
        'docx': 'description',
        'xls': 'table_chart',
        'xlsx': 'table_chart',
        'ppt': 'slideshow',
        'pptx': 'slideshow',
        'zip': 'folder_zip',
        'rar': 'folder_zip',
        '7z': 'folder_zip',
        'jpg': 'image',
        'jpeg': 'image',
        'png': 'image',
        'gif': 'image',
        'svg': 'image',
        'mp4': 'videocam',
        'avi': 'videocam',
        'mov': 'videocam',
        'mp3': 'audio_file',
        'wav': 'audio_file',
        'txt': 'article',
        'html': 'code',
        'css': 'code',
        'js': 'code',
        'json': 'code',
        'php': 'code',
    };

    if (extIcons[ext]) return extIcons[ext];

    // By mime type
    if (mimeType.startsWith('image/')) return 'image';
    if (mimeType.startsWith('video/')) return 'videocam';
    if (mimeType.startsWith('audio/')) return 'audio_file';
    if (mimeType.startsWith('text/')) return 'article';

    return 'draft';
}

// Form validation
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    let hasFiles = false;

    if (currentMode === 'file') {
        hasFiles = document.getElementById('file-single').files.length > 0;
    } else if (currentMode === 'multiple') {
        hasFiles = document.getElementById('file-multiple').files.length > 0;
    } else if (currentMode === 'folder') {
        hasFiles = document.getElementById('file-folder').files.length > 0;
    }

    if (!hasFiles) {
        e.preventDefault();
        alert('Silakan pilih file untuk diupload');
        return false;
    }
});
</script>
@endsection
