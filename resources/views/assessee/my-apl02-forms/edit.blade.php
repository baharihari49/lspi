@extends('layouts.admin')

@section('title', 'Edit APL-02 - ' . $apl02Form->form_number)

@php $active = 'my-apl02-forms'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-apl02-forms.index') }}" class="hover:text-blue-600">APL-02 Saya</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <a href="{{ route('admin.my-apl02-forms.show', $apl02Form) }}" class="hover:text-blue-600">{{ $apl02Form->form_number }}</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-900">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit APL-02</h1>
            <p class="text-gray-600 mt-1">{{ $apl02Form->scheme->name ?? '-' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Revision Notes Warning -->
    @if($apl02Form->status === 'revision_required' && $apl02Form->revision_notes)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <h3 class="font-bold text-orange-900 flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined">warning</span>
                Catatan Revisi dari Assessor
            </h3>
            <ul class="list-disc list-inside text-sm text-orange-800 space-y-1">
                @foreach($apl02Form->revision_notes as $note)
                    <li>{{ $note }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.my-apl02-forms.update', $apl02Form) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Self Assessment -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Asesmen Mandiri</h3>
                    <p class="text-sm text-gray-600 mb-6">Evaluasi kompetensi diri Anda untuk setiap unit kompetensi pada skema ini.</p>

                    @if($apl02Form->self_assessment && count($apl02Form->self_assessment) > 0)
                        <div class="space-y-6">
                            @foreach($apl02Form->self_assessment as $index => $assessment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="mb-4">
                                        <p class="text-xs text-gray-500 mb-1">Unit {{ $index + 1 }}</p>
                                        <p class="text-sm font-mono text-gray-600">{{ $assessment['unit_code'] ?? '-' }}</p>
                                        <p class="font-semibold text-gray-900">{{ $assessment['unit_title'] ?? '-' }}</p>
                                    </div>

                                    <input type="hidden" name="self_assessment[{{ $index }}][unit_id]" value="{{ $assessment['unit_id'] }}">

                                    <div class="space-y-4">
                                        <!-- Competency Self-Assessment -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Apakah Anda merasa kompeten pada unit ini?</label>
                                            <div class="flex items-center gap-4">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="self_assessment[{{ $index }}][is_competent]" value="1"
                                                        {{ isset($assessment['is_competent']) && $assessment['is_competent'] === true ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                                    <span class="text-sm text-gray-700">Ya, Kompeten</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="self_assessment[{{ $index }}][is_competent]" value="0"
                                                        {{ isset($assessment['is_competent']) && $assessment['is_competent'] === false ? 'checked' : '' }}
                                                        class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                                    <span class="text-sm text-gray-700">Belum Kompeten</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Evidence Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Bukti Kompetensi</label>
                                            <textarea name="self_assessment[{{ $index }}][evidence_description]" rows="3"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                                placeholder="Jelaskan bukti/pengalaman yang menunjukkan kompetensi Anda pada unit ini...">{{ $assessment['evidence_description'] ?? '' }}</textarea>
                                        </div>

                                        <!-- Notes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                                            <textarea name="self_assessment[{{ $index }}][notes]" rows="2"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                                                placeholder="Catatan tambahan (opsional)...">{{ $assessment['notes'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">info</span>
                            <p class="text-sm">Data asesmen mandiri belum diinisialisasi</p>
                        </div>
                    @endif
                </div>

                <!-- Portfolio Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Portfolio</h3>
                    <p class="text-sm text-gray-600 mb-4">Jelaskan secara ringkas portfolio dan pengalaman Anda terkait skema sertifikasi ini.</p>

                    <textarea name="portfolio_summary" rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        placeholder="Ringkasan portfolio dan pengalaman Anda...">{{ old('portfolio_summary', $apl02Form->portfolio_summary) }}</textarea>
                </div>

                <!-- Evidence Files Section - Note: Upload form is outside main form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="evidenceSection">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">File Bukti</h3>

                    <!-- Existing Files -->
                    @if($apl02Form->evidence_files && count($apl02Form->evidence_files) > 0)
                        <div class="mb-4 space-y-2">
                            @foreach($apl02Form->evidence_files as $index => $file)
                                <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-gray-400">attach_file</span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $file['name'] ?? 'File ' . ($index + 1) }}</p>
                                            <p class="text-xs text-gray-500">{{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : '' }}</p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-red-600 hover:text-red-800" onclick="deleteEvidence({{ $index }})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Upload New Files - Using JavaScript to handle -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <span class="material-symbols-outlined text-4xl text-gray-400">cloud_upload</span>
                        <p class="mt-2 text-sm text-gray-600">Upload file bukti pendukung</p>
                        <p class="text-xs text-gray-500">PDF, DOC, JPG, PNG (max 10MB per file)</p>
                        <input type="file" name="files[]" multiple class="hidden" id="evidenceFiles" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('evidenceFiles').click()" class="mt-3 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-sm font-semibold transition">
                            Pilih File
                        </button>
                        <div id="selectedFiles" class="mt-3 text-sm text-gray-600"></div>
                        <button type="button" id="uploadBtn" onclick="uploadEvidence()" class="hidden mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
                            Upload File
                        </button>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deklarasi</h3>

                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-900">
                            Dengan ini saya menyatakan bahwa semua informasi yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan. Saya memahami bahwa memberikan informasi palsu dapat mengakibatkan pembatalan sertifikasi.
                        </p>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="declaration_agreed" value="1"
                            {{ $apl02Form->declaration_agreed ? 'checked' : '' }}
                            class="w-5 h-5 mt-0.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">
                            Saya setuju dengan deklarasi di atas dan menyatakan bahwa semua informasi yang saya berikan adalah benar.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.my-apl02-forms.show', $apl02Form) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                            Batal
                        </a>
                    </div>

                    <!-- Progress Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Progress</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $apl02Form->completion_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $apl02Form->completion_percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Progress akan diupdate setelah menyimpan
                        </p>
                    </div>

                    <!-- Submit Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">Submit untuk Review</h4>
                        <p class="text-xs text-gray-500 mb-3">Pastikan semua asesmen mandiri sudah diisi dan deklarasi disetujui sebelum submit.</p>

                        @php
                            $canSubmit = $apl02Form->status === 'draft' && $apl02Form->declaration_agreed && $apl02Form->hasAllSelfAssessmentFilled();
                        @endphp

                        @if($canSubmit)
                            <button type="button" onclick="submitForm()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">send</span>
                                Submit untuk Review
                            </button>
                        @else
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-yellow-800">
                                    <span class="font-semibold">Belum bisa submit:</span>
                                    <ul class="mt-1 list-disc list-inside">
                                        @if(!$apl02Form->hasAllSelfAssessmentFilled())
                                            <li>Lengkapi semua asesmen mandiri</li>
                                        @endif
                                        @if(!$apl02Form->declaration_agreed)
                                            <li>Setujui deklarasi</li>
                                        @endif
                                    </ul>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Form Number</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->form_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Skema</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Event</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->event->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Unit</p>
                            <p class="font-semibold text-gray-900">{{ count($apl02Form->self_assessment ?? []) }} unit</p>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined">help</span>
                        Petunjuk Pengisian
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li>1. Isi asesmen mandiri untuk setiap unit</li>
                        <li>2. Upload file bukti pendukung</li>
                        <li>3. Setujui deklarasi</li>
                        <li>4. Submit form untuk direview</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('evidenceFiles').addEventListener('change', function(e) {
        const files = e.target.files;
        const selectedFilesDiv = document.getElementById('selectedFiles');
        const uploadBtn = document.getElementById('uploadBtn');

        if (files.length > 0) {
            let html = '<ul class="text-left">';
            for (let i = 0; i < files.length; i++) {
                html += '<li class="text-gray-700">' + files[i].name + ' (' + (files[i].size / 1024).toFixed(2) + ' KB)</li>';
            }
            html += '</ul>';
            selectedFilesDiv.innerHTML = html;
            uploadBtn.classList.remove('hidden');
        } else {
            selectedFilesDiv.innerHTML = '';
            uploadBtn.classList.add('hidden');
        }
    });

    function uploadEvidence() {
        const fileInput = document.getElementById('evidenceFiles');
        const files = fileInput.files;

        if (files.length === 0) {
            alert('Pilih file terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = 'Uploading...';

        fetch('{{ route('admin.my-apl02-forms.upload-evidence', $apl02Form) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengupload file. Silakan coba lagi.');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = 'Upload File';
        });
    }

    function deleteEvidence(index) {
        if (!confirm('Hapus file ini?')) {
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        formData.append('file_index', index);

        fetch('{{ route('admin.my-apl02-forms.delete-evidence', $apl02Form) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus file. Silakan coba lagi.');
        });
    }

    function submitForm() {
        if (!confirm('Apakah Anda yakin ingin submit APL-02 ini untuk review? Setelah submit, Anda tidak dapat mengubah data kecuali diminta revisi.')) {
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('admin.my-apl02-forms.submit', $apl02Form) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.href = '{{ route('admin.my-apl02-forms.show', $apl02Form) }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal submit form. Silakan coba lagi.');
        });
    }
</script>
@endsection
