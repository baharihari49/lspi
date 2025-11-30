@extends('layouts.admin')

@section('title', 'APL-02 Form Details - ' . $apl02Form->form_number)

@php
    $active = 'apl02-forms';
@endphp

@section('page_title', 'APL-02 Form Details')
@section('page_description', $apl02Form->form_number)

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
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Form Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">tag</span>
                        <div>
                            <p class="text-xs text-gray-600">Form Number</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->form_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">person</span>
                        <div>
                            <p class="text-xs text-gray-600">Assessee</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->assessee->full_name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">verified</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'submitted' => 'bg-blue-100 text-blue-800',
                                    'under_review' => 'bg-yellow-100 text-yellow-800',
                                    'revision_required' => 'bg-orange-100 text-orange-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$apl02Form->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $apl02Form->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">assessment</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Assessment Result</p>
                            @php
                                $resultColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $resultColors[$apl02Form->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $apl02Form->assessment_result_label }}
                            </span>
                        </div>
                    </div>

                    @if($apl02Form->assessor)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">supervisor_account</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessor</p>
                                <p class="font-semibold text-gray-900">{{ $apl02Form->assessor->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->event)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Event</p>
                                <p class="font-semibold text-gray-900">{{ $apl02Form->event->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->apl01Form)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">description</span>
                            <div>
                                <p class="text-xs text-gray-600">APL-01 Form</p>
                                <a href="{{ route('admin.apl01.show', $apl02Form->apl01Form) }}" class="font-semibold text-blue-600 hover:underline">
                                    {{ $apl02Form->apl01Form->form_number }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Completion Progress</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Overall Completion</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $apl02Form->completion_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $apl02Form->completion_percentage }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-900">{{ count($apl02Form->evidence_files ?? []) }}</p>
                            <p class="text-xs text-blue-700 mt-1">Evidence Files</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-900">{{ $apl02Form->scheme->units->count() ?? 0 }}</p>
                            <p class="text-xs text-purple-700 mt-1">Total Units</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Self Assessment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Self Assessment</h3>

                @if($apl02Form->self_assessment && count($apl02Form->self_assessment) > 0)
                    <div class="space-y-4">
                        @foreach($apl02Form->self_assessment as $assessment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $assessment['unit_code'] ?? '-' }}</p>
                                        <p class="text-xs text-gray-600">{{ $assessment['unit_title'] ?? '-' }}</p>
                                    </div>
                                    @if(isset($assessment['is_competent']))
                                        @if($assessment['is_competent'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Kompeten</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Belum Kompeten</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Belum Diisi</span>
                                    @endif
                                </div>
                                @if(!empty($assessment['evidence_description']))
                                    <div class="mt-2 text-sm text-gray-700">
                                        <p class="text-xs text-gray-500">Bukti Kompetensi:</p>
                                        <p>{{ $assessment['evidence_description'] }}</p>
                                    </div>
                                @endif
                                @if(!empty($assessment['notes']))
                                    <div class="mt-2 text-sm text-gray-700">
                                        <p class="text-xs text-gray-500">Catatan:</p>
                                        <p>{{ $assessment['notes'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">assignment</span>
                        <p class="text-sm">Self assessment belum diisi</p>
                    </div>
                @endif
            </div>

            <!-- Portfolio Summary -->
            @if($apl02Form->portfolio_summary)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Portfolio Summary</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($apl02Form->portfolio_summary)) !!}
                    </div>
                </div>
            @endif

            <!-- Evidence Files -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Files</h3>

                @if($apl02Form->evidence_files && count($apl02Form->evidence_files) > 0)
                    <div class="space-y-3">
                        @foreach($apl02Form->evidence_files as $index => $file)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-gray-400">attach_file</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $file['name'] ?? 'File ' . ($index + 1) }}</p>
                                            <p class="text-xs text-gray-500">{{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : '' }}</p>
                                        </div>
                                    </div>
                                    @if(isset($file['path']))
                                        <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">folder_open</span>
                        <p class="text-sm">No evidence files uploaded</p>
                    </div>
                @endif
            </div>

            <!-- Assessor Feedback -->
            @if($apl02Form->assessor_notes || $apl02Form->assessor_feedback || $apl02Form->recommendations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Feedback</h3>

                    @if($apl02Form->assessor_notes)
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 mb-2">Notes</p>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $apl02Form->assessor_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->assessor_feedback)
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 mb-2">Feedback</p>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-900 whitespace-pre-wrap">{{ $apl02Form->assessor_feedback }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->recommendations)
                        <div>
                            <p class="text-xs text-gray-600 mb-2">Recommendations</p>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-900 whitespace-pre-wrap">{{ $apl02Form->recommendations }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Revision Notes -->
            @if($apl02Form->revision_notes && count($apl02Form->revision_notes) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Revision Notes</h3>

                    <div class="bg-orange-50 rounded-lg p-4">
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($apl02Form->revision_notes as $note)
                                <li class="text-sm text-orange-900">{{ $note }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Created</p>
                            <p class="text-xs text-gray-500">{{ $apl02Form->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($apl02Form->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->submitted_at->format('d M Y H:i') }}</p>
                                @if($apl02Form->submittedBy)
                                    <p class="text-xs text-gray-500">by {{ $apl02Form->submittedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->assigned_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">assignment_ind</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Assigned to Assessor</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->assigned_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->review_started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">play_arrow</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Review Started</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->review_completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Review Completed</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->review_completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl02Form->completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">task_alt</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Completed</p>
                                <p class="text-xs text-gray-500">{{ $apl02Form->completed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.apl02-forms.edit', $apl02Form) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Form
                    </a>

                    @if(in_array($apl02Form->status, ['submitted', 'under_review']))
                        <form action="{{ route('admin.apl02-forms.approve', $apl02Form) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-center transition-all">
                                Approve
                            </button>
                        </form>

                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="block w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 text-center transition-all">
                            Reject
                        </button>

                        <button type="button" onclick="document.getElementById('revisionModal').classList.remove('hidden')" class="block w-full h-12 px-4 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 text-center transition-all">
                            Request Revision
                        </button>
                    @endif

                    <a href="{{ route('admin.apl02-forms.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $apl02Form->created_at->format('d M Y H:i') }}
                    </p>
                    @if($apl02Form->updated_at != $apl02Form->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $apl02Form->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Declaration Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Declaration</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Agreed</span>
                        @if($apl02Form->declaration_agreed)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>

                    @if($apl02Form->declaration_signed_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Signed At</span>
                            <span class="text-sm text-gray-900">{{ $apl02Form->declaration_signed_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($apl02Form->admin_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Notes</h3>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-sm text-yellow-900 whitespace-pre-wrap">{{ $apl02Form->admin_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject APL-02 Form</h3>
            <form action="{{ route('admin.apl02-forms.reject', $apl02Form) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection *</label>
                    <textarea name="assessor_notes" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none" placeholder="Explain why this form is being rejected..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Feedback</label>
                    <textarea name="assessor_feedback" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none" placeholder="Any additional feedback..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revision Modal -->
    <div id="revisionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Request Revision</h3>
            <form action="{{ route('admin.apl02-forms.request-revision', $apl02Form) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Revision Notes *</label>
                    <div id="revisionNotesContainer">
                        <input type="text" name="revision_notes[]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none mb-2" placeholder="What needs to be revised...">
                    </div>
                    <button type="button" onclick="addRevisionNote()" class="text-sm text-orange-600 hover:text-orange-800 font-semibold">
                        + Add another note
                    </button>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
                        Request Revision
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addRevisionNote() {
            const container = document.getElementById('revisionNotesContainer');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'revision_notes[]';
            input.className = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none mb-2';
            input.placeholder = 'What needs to be revised...';
            container.appendChild(input);
        }
    </script>
@endsection
