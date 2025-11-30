@extends('layouts.admin')

@section('title', 'APL-01 Form Detail - ' . $apl01->form_number)

@php
    $active = 'apl01';
@endphp

@section('page_title', $apl01->form_number)
@section('page_description', 'APL-01 Form Details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Status Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Form Status</h3>
                    @if($apl01->is_editable)
                        <a href="{{ route('admin.apl01.edit', $apl01) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Form</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'revised' => 'bg-orange-100 text-orange-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$apl01->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $apl01->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Review Level</p>
                        <p class="font-semibold text-gray-900">
                            @if($apl01->current_review_level > 0)
                                Level {{ $apl01->current_review_level }}
                            @else
                                Not Started
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Completion</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $apl01->completion_percentage }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $apl01->completion_percentage }}%</span>
                        </div>
                    </div>
                </div>

                @if($apl01->submitted_at)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600">Submitted: {{ $apl01->submitted_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>

            <!-- Assessee Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessee Information</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($apl01->full_name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $apl01->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $apl01->id_number }}</p>
                            @if($apl01->assessee)
                                <p class="text-sm text-gray-600">{{ $apl01->assessee->registration_number ?? 'No Registration Number' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($apl01->email)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">mail</span>
                                <div>
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->mobile)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                                <div>
                                    <p class="text-xs text-gray-600">Mobile</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->mobile }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->city)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">location_on</span>
                                <div>
                                    <p class="text-xs text-gray-600">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->city }}{{ $apl01->province ? ', ' . $apl01->province : '' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->date_of_birth)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">cake</span>
                                <div>
                                    <p class="text-xs text-gray-600">Birth</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $apl01->place_of_birth ? $apl01->place_of_birth . ', ' : '' }}{{ $apl01->date_of_birth->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->gender)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">person</span>
                                <div>
                                    <p class="text-xs text-gray-600">Gender</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($apl01->gender) }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->nationality)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">flag</span>
                                <div>
                                    <p class="text-xs text-gray-600">Nationality</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->nationality }}</p>
                                </div>
                            </div>
                        @endif

                        @if($apl01->current_company)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">business</span>
                                <div>
                                    <p class="text-xs text-gray-600">Company</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->current_company }}</p>
                                    @if($apl01->current_position)
                                        <p class="text-xs text-gray-500">{{ $apl01->current_position }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($apl01->current_industry)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">category</span>
                                <div>
                                    <p class="text-xs text-gray-600">Industry</p>
                                    <p class="font-semibold text-gray-900">{{ $apl01->current_industry }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($apl01->address)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Full Address</p>
                            <p class="text-sm text-gray-700">{{ $apl01->address }}{{ $apl01->city ? ', ' . $apl01->city : '' }}{{ $apl01->province ? ', ' . $apl01->province : '' }}{{ $apl01->postal_code ? ' ' . $apl01->postal_code : '' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Scheme Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Scheme</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Scheme Name</p>
                            <p class="font-semibold text-gray-900">{{ $apl01->scheme->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">tag</span>
                        <div>
                            <p class="text-xs text-gray-600">Scheme Code</p>
                            <p class="font-semibold text-gray-900">{{ $apl01->scheme->code }}</p>
                        </div>
                    </div>

                    @if($apl01->event)
                        <div class="md:col-span-2 flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Event</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->event->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($apl01->tuk)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600">apartment</span>
                            <div>
                                <p class="text-xs text-gray-600">TUK (Tempat Uji Kompetensi)</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->tuk->name }}</p>
                                @if($apl01->tuk->city || $apl01->tuk->province)
                                    <p class="text-xs text-gray-500">{{ $apl01->tuk->city }}{{ $apl01->tuk->city && $apl01->tuk->province ? ', ' : '' }}{{ $apl01->tuk->province }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($apl01->eventSession)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-purple-600">calendar_month</span>
                            <div>
                                <p class="text-xs text-gray-600">Session</p>
                                <p class="font-semibold text-gray-900">{{ $apl01->eventSession->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($apl01->eventSession->session_date)->format('d M Y') }}
                                    • {{ \Carbon\Carbon::parse($apl01->eventSession->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($apl01->eventSession->end_time)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($apl01->certification_purpose || $apl01->target_competency)
                    <div class="pt-4 border-t border-gray-200 mt-4 space-y-3">
                        @if($apl01->certification_purpose)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Certification Purpose</p>
                                <p class="text-sm text-gray-700">{{ $apl01->certification_purpose }}</p>
                            </div>
                        @endif

                        @if($apl01->target_competency)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Target Competency</p>
                                <p class="text-sm text-gray-700">{{ $apl01->target_competency }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Review History -->
            @if($apl01->reviews->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Review History ({{ $apl01->reviews->count() }})</h3>

                    <div class="space-y-4">
                        @foreach($apl01->reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900">{{ $review->review_level_name ?? 'Level ' . $review->review_level }}</span>
                                            @if($review->is_current)
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">Current</span>
                                            @endif
                                            @if($review->is_final)
                                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded">Final</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">Reviewer: {{ $review->reviewer->name }}</p>
                                    </div>
                                    @php
                                        $decisionColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'approved_with_notes' => 'bg-blue-100 text-blue-800',
                                            'returned' => 'bg-orange-100 text-orange-800',
                                            'forwarded' => 'bg-purple-100 text-purple-800',
                                            'on_hold' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $review->decision_label }}
                                    </span>
                                </div>
                                @if($review->review_notes)
                                    <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                        <p class="text-sm text-gray-700">{{ $review->review_notes }}</p>
                                    </div>
                                @endif
                                @if($review->score)
                                    <div class="mt-3">
                                        <span class="text-xs text-gray-600">Score: </span>
                                        <span class="font-semibold text-gray-900">{{ $review->score }}</span>
                                    </div>
                                @endif
                                <div class="flex gap-4 mt-3 text-xs text-gray-500">
                                    <span>Assigned: {{ $review->assigned_at?->format('d M Y H:i') }}</span>
                                    @if($review->completed_at)
                                        <span>Completed: {{ $review->completed_at->format('d M Y H:i') }}</span>
                                    @endif
                                    @if($review->review_duration)
                                        <span>Duration: {{ $review->review_duration }} mins</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Declaration Status -->
            @if($apl01->declaration_agreed)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
                        <div>
                            <p class="font-medium text-green-900">Declaration Agreed</p>
                            <p class="text-sm text-green-700">Signed at: {{ $apl01->declaration_signed_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-yellow-600 mr-3">warning</span>
                        <div>
                            <p class="font-medium text-yellow-900">Declaration Not Yet Agreed</p>
                            <p class="text-sm text-yellow-700">The declaration must be agreed before submission</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if($apl01->is_editable)
                        <a href="{{ route('admin.apl01.edit', $apl01) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Edit Form
                        </a>
                    @endif

                    @if($apl01->canBeSubmitted())
                        <form action="{{ route('admin.apl01.submit', $apl01) }}" method="POST" onsubmit="return confirm('Submit this form for review?')">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all">
                                Submit for Review
                            </button>
                        </form>
                    @endif

                    @if($apl01->status === 'submitted')
                        @php
                            // Get the auto-assigned reviewer info
                            $autoReviewerId = $apl01->getReviewerFromEventAssessors();
                            $autoReviewer = $autoReviewerId ? \App\Models\User::find($autoReviewerId) : null;
                        @endphp
                        @if($autoReviewer)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <p class="text-xs text-blue-700 mb-1">Reviewer akan di-assign ke:</p>
                                <p class="font-semibold text-blue-900">{{ $autoReviewer->name }}</p>
                                <p class="text-xs text-blue-600">{{ $autoReviewer->email }}</p>
                            </div>
                        @endif
                        <form action="{{ route('admin.apl01.accept-review', $apl01) }}" method="POST" onsubmit="return confirm('Terima form ini untuk review?')">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">check_circle</span>
                                Accept for Review
                            </button>
                        </form>
                    @endif

                    {{-- HIDDEN: APL-02 per-unit generation temporarily disabled - will be replaced with APL-02 per-scheme --}}
                    {{-- @if($apl01->status === 'approved' && !$apl01->apl02_generated_at)
                        <form action="{{ route('admin.apl01.generate-apl02', $apl01) }}" method="POST" onsubmit="return confirm('Generate APL-02 untuk form ini?')">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">auto_fix_high</span>
                                Generate APL-02
                            </button>
                        </form>
                    @elseif($apl01->apl02_generated_at)
                        <div class="w-full h-12 px-4 bg-purple-100 text-purple-700 font-semibold rounded-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            APL-02 Generated
                        </div>
                    @endif --}}

                    {{-- APL-02 Form (per-Scheme) - NEW FLOW --}}
                    @if($apl01->status === 'approved' && !$apl01->apl02Form)
                        <form action="{{ route('admin.certification-flow.generate-apl02', $apl01) }}" method="POST" onsubmit="return confirm('Generate APL-02 Form untuk form ini?')">
                            @csrf
                            <button type="submit" class="w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">auto_fix_high</span>
                                Generate APL-02 Form
                            </button>
                        </form>
                    @elseif($apl01->apl02Form)
                        <a href="{{ route('admin.apl02-forms.show', $apl01->apl02Form) }}" class="w-full h-12 px-4 bg-purple-100 text-purple-700 font-semibold rounded-lg hover:bg-purple-200 flex items-center justify-center gap-2 transition">
                            <span class="material-symbols-outlined">assignment</span>
                            Lihat APL-02 Form
                        </a>
                    @endif

                    <a href="{{ route('admin.apl01.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    @if($apl01->status === 'draft')
                        <form action="{{ route('admin.apl01.destroy', $apl01) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this form?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                                Delete Form
                            </button>
                        </form>
                    @endif
                </div>

                @if($apl01->completed_at)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            <span class="font-semibold">Completed:</span><br>
                            {{ $apl01->completed_at->format('d M Y H:i') }}<br>
                            @if($apl01->completedBy)
                                by {{ $apl01->completedBy->name }}
                            @endif
                        </p>
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $apl01->created_at->format('d M Y H:i') }}
                    </p>
                    @if($apl01->updated_at != $apl01->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $apl01->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Review Level</span>
                        <span class="font-bold text-gray-900">{{ $apl01->current_review_level > 0 ? 'Level ' . $apl01->current_review_level : 'Not Started' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Reviews</span>
                        <span class="font-bold text-gray-900">{{ $apl01->reviews->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Completion</span>
                        <span class="font-bold text-gray-900">{{ $apl01->completion_percentage }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Answers</span>
                        <span class="font-bold text-gray-900">{{ $apl01->answers->count() }}</span>
                    </div>
                    {{-- HIDDEN: APL-02 per-unit stats temporarily disabled --}}
                    {{-- @if($apl01->apl02_generated_at)
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">APL-02 Units</span>
                            <span class="font-bold text-purple-600">{{ $apl01->apl02Units->count() }}</span>
                        </div>
                    @endif --}}
                </div>
            </div>

            <!-- Certification Flow Status -->
            @if($apl01->status === 'approved')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Flow</h3>

                    {{-- Use the certification flow status component --}}
                    <x-admin.certification-flow-status :apl01="$apl01" :compact="true" />

                    {{-- Flow Details --}}
                    <div class="mt-4 space-y-2 text-sm">
                        {{-- HIDDEN: APL-02 per-unit flow details temporarily disabled --}}
                        {{-- @if($apl01->apl02_generated_at)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>APL-02 dibuat:</span>
                                <span class="font-medium">{{ $apl01->apl02_generated_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif --}}
                        @if($apl01->assessment_scheduled_at)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Asesmen dijadwalkan:</span>
                                <span class="font-medium">{{ $apl01->assessment_scheduled_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                        @if($apl01->certificate_issued_at)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Sertifikat diterbitkan:</span>
                                <span class="font-medium">{{ $apl01->certificate_issued_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Links --}}
                    <div class="mt-4 space-y-2">
                        {{-- HIDDEN: APL-02 per-unit link temporarily disabled --}}
                        {{-- @if($apl01->apl02_generated_at)
                            <a href="{{ route('admin.apl02.units.index', ['apl01_form_id' => $apl01->id]) }}"
                               class="block w-full text-center px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition text-sm font-medium">
                                Lihat Unit APL-02 →
                            </a>
                        @endif --}}
                        @if($apl01->flow_status === 'certificate_issued')
                            <a href="{{ route('admin.certificates.index', ['assessee_id' => $apl01->assessee_id]) }}"
                               class="block w-full text-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                                Lihat Sertifikat →
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
