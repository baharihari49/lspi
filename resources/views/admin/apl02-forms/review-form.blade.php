@extends('layouts.admin')

@section('title', 'Review APL-02 Form')

@php
    $active = 'apl02-form-reviews';
@endphp

@section('page_title', 'Review APL-02 Form')
@section('page_description', 'Form: ' . $apl02Form->form_number)

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

    <!-- Header with Actions -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.apl02-form-reviews.my-reviews') }}" class="flex items-center text-gray-600 hover:text-gray-900">
            <span class="material-symbols-outlined mr-1">arrow_back</span>
            Kembali
        </a>
        <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('requestRevisionModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">edit_note</span>
                Minta Revisi
            </button>
            <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">cancel</span>
                Tolak
            </button>
            <button type="button" onclick="document.getElementById('approveModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">check_circle</span>
                Setujui
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Form</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">No. Form</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->form_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-yellow-100 text-yellow-800',
                                'under_review' => 'bg-blue-100 text-blue-800',
                                'revision_required' => 'bg-orange-100 text-orange-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$apl02Form->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $apl02Form->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Skema</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->event->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Submit</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Progress</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full">
                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $apl02Form->completion_percentage }}%"></div>
                            </div>
                            <span class="text-sm font-semibold">{{ $apl02Form->completion_percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessee Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Asesi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. APL-01</p>
                        <a href="{{ route('admin.apl01.show', $apl02Form->apl01Form) }}" class="font-semibold text-blue-600 hover:underline">
                            {{ $apl02Form->apl01Form->form_number ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Instansi</p>
                        <p class="font-semibold text-gray-900">{{ $apl02Form->apl01Form->assessee->institution ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Self Assessment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Asesmen Mandiri</h3>
                @php
                    $selfAssessment = $apl02Form->self_assessment ?? [];
                @endphp
                @if(count($selfAssessment) > 0)
                    <div class="space-y-4">
                        @foreach($selfAssessment as $index => $unit)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $unit['unit_code'] ?? 'Unit ' . ($index + 1) }}</p>
                                        <p class="text-sm text-gray-600">{{ $unit['unit_title'] ?? '-' }}</p>
                                    </div>
                                    @if(isset($unit['is_competent']))
                                        @if($unit['is_competent'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-symbols-outlined text-sm mr-1">check</span>
                                                Kompeten
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="material-symbols-outlined text-sm mr-1">close</span>
                                                Belum Kompeten
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Belum Diisi
                                        </span>
                                    @endif
                                </div>
                                @if(!empty($unit['evidence_description']))
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-sm text-gray-500 mb-1">Deskripsi Bukti:</p>
                                        <p class="text-sm text-gray-700">{{ $unit['evidence_description'] }}</p>
                                    </div>
                                @endif
                                @if(!empty($unit['notes']))
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-1">Catatan:</p>
                                        <p class="text-sm text-gray-700">{{ $unit['notes'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada data asesmen mandiri</p>
                @endif
            </div>

            <!-- Evidence Files -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bukti Pendukung</h3>
                @php
                    $evidenceFiles = $apl02Form->evidence_files ?? [];
                @endphp
                @if(count($evidenceFiles) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($evidenceFiles as $file)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <span class="material-symbols-outlined text-gray-500 mr-3">description</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $file['name'] ?? 'File' }}</p>
                                    <p class="text-xs text-gray-500">{{ $file['type'] ?? 'Unknown' }}</p>
                                </div>
                                <a href="{{ Storage::url($file['path'] ?? '') }}" target="_blank" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <span class="material-symbols-outlined">download</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Tidak ada bukti pendukung</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan</h3>
                @php
                    $totalUnits = count($selfAssessment);
                    $completedUnits = collect($selfAssessment)->filter(fn($u) => isset($u['is_competent']))->count();
                    $competentUnits = collect($selfAssessment)->filter(fn($u) => ($u['is_competent'] ?? false) === true)->count();
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Unit</span>
                        <span class="font-semibold text-gray-900">{{ $totalUnits }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Unit Terisi</span>
                        <span class="font-semibold text-gray-900">{{ $completedUnits }} / {{ $totalUnits }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kompeten</span>
                        <span class="font-semibold text-green-600">{{ $competentUnits }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Belum Kompeten</span>
                        <span class="font-semibold text-red-600">{{ $completedUnits - $competentUnits }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Bukti Pendukung</span>
                        <span class="font-semibold text-gray-900">{{ count($evidenceFiles) }} file</span>
                    </div>
                </div>
            </div>

            <!-- Review Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600 text-sm">edit</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $apl02Form->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($apl02Form->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-yellow-600 text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->submitted_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($apl02Form->review_started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 text-sm">rate_review</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Review Dimulai</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($apl02Form->review_completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Review Selesai</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="document.getElementById('approveModal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Setujui APL-02 Form</h3>
                <form action="{{ route('admin.apl02-form-reviews.approve', $apl02Form) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Assessor</label>
                        <textarea name="assessor_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan opsional..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Feedback untuk Asesi</label>
                        <textarea name="assessor_feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Feedback opsional..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak APL-02 Form</h3>
                <form action="{{ route('admin.apl02-form-reviews.reject', $apl02Form) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="assessor_notes" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Feedback untuk Asesi</label>
                        <textarea name="assessor_feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Feedback opsional..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Revision Modal -->
    <div id="requestRevisionModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="document.getElementById('requestRevisionModal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Minta Revisi</h3>
                <form action="{{ route('admin.apl02-form-reviews.request-revision', $apl02Form) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Revisi <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-2">Jelaskan apa yang perlu diperbaiki</p>
                        <div id="revisionNotes">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="revision_notes[]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Item revisi...">
                                <button type="button" onclick="addRevisionNote()" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                    <span class="material-symbols-outlined">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('requestRevisionModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                            Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addRevisionNote() {
            const container = document.getElementById('revisionNotes');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
                <input type="text" name="revision_notes[]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Item revisi...">
                <button type="button" onclick="this.parentElement.remove()" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                    <span class="material-symbols-outlined">remove</span>
                </button>
            `;
            container.appendChild(div);
        }
    </script>
@endsection
