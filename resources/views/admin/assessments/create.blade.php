@extends('layouts.admin')

@section('title', 'Create Assessment')

@php
    $active = 'assessments';
@endphp

@section('page_title', 'Create New Assessment')
@section('page_description', 'Add a new competency assessment')

@section('content')
    <form action="{{ route('admin.assessments.store') }}" method="POST" class="w-full" id="assessmentForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Selection (Required First) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-900 text-white text-sm rounded-full mr-2">1</span>
                        Pilih Event
                    </h3>

                    <div>
                        <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">Event Sertifikasi *</label>
                        <select id="event_id" name="event_id" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_id') border-red-500 @enderror">
                            <option value="">-- Pilih Event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}"
                                    data-scheme-id="{{ $event->scheme_id }}"
                                    data-scheme-name="{{ $event->scheme?->name }}"
                                    data-scheme-code="{{ $event->scheme?->code }}"
                                    data-start-date="{{ $event->start_date?->format('Y-m-d') }}"
                                    data-location="{{ $event->location }}"
                                    {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} ({{ $event->start_date?->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Pilih event terlebih dahulu untuk mengisi data skema, asesor, dan asesi secara otomatis</p>
                    </div>
                </div>

                <!-- Auto-filled Data from Event -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="eventDataSection" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-900 text-white text-sm rounded-full mr-2">2</span>
                        Data dari Event
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Scheme (Auto-filled, readonly display) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Skema Sertifikasi</label>
                            <div id="schemeDisplay" class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-gray-50 flex items-center text-gray-700">
                                -
                            </div>
                            <input type="hidden" id="scheme_id" name="scheme_id" value="{{ old('scheme_id') }}">
                        </div>

                        <!-- Lead Assessor (Auto-filled) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lead Assessor</label>
                            <div id="leadAssessorDisplay" class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-gray-50 flex items-center text-gray-700">
                                -
                            </div>
                            <input type="hidden" id="lead_assessor_id" name="lead_assessor_id" value="{{ old('lead_assessor_id') }}">
                        </div>

                        <!-- Assessee Selection -->
                        <div class="md:col-span-2">
                            <label for="assessee_id" class="block text-sm font-semibold text-gray-700 mb-2">Asesi *</label>
                            <select id="assessee_id" name="assessee_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessee_id') border-red-500 @enderror">
                                <option value="">-- Pilih Asesi --</option>
                            </select>
                            @error('assessee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500" id="assesseeHint">Daftar asesi yang terdaftar pada event ini</p>
                        </div>

                        <!-- TUK from Event -->
                        <div class="md:col-span-2">
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">TUK (Tempat Uji Kompetensi)</label>
                            <select id="tuk_id" name="tuk_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">-- Pilih TUK --</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
                                        {{ $tuk->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="assessmentDetailsSection" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-900 text-white text-sm rounded-full mr-2">3</span>
                        Detail Assessment
                    </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Assessment *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror"
                                placeholder="Contoh: Assessment Pengelolaan Jurnal Elektronik - Batch 1">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Deskripsi singkat tentang assessment ini">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Assessment Type -->
                            <div>
                                <label for="assessment_type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Assessment *</label>
                                <select id="assessment_type" name="assessment_type" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_type') border-red-500 @enderror">
                                    <option value="initial" {{ old('assessment_type', 'initial') === 'initial' ? 'selected' : '' }}>Initial Assessment</option>
                                    <option value="verification" {{ old('assessment_type') === 'verification' ? 'selected' : '' }}>Verification</option>
                                    <option value="surveillance" {{ old('assessment_type') === 'surveillance' ? 'selected' : '' }}>Surveillance</option>
                                    <option value="re_assessment" {{ old('assessment_type') === 're_assessment' ? 'selected' : '' }}>Re-Assessment</option>
                                </select>
                                @error('assessment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assessment Method -->
                            <div>
                                <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Metode Assessment *</label>
                                <select id="assessment_method" name="assessment_method" required
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_method') border-red-500 @enderror">
                                    <option value="portfolio" {{ old('assessment_method', 'portfolio') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="observation" {{ old('assessment_method') === 'observation' ? 'selected' : '' }}>Observation</option>
                                    <option value="interview" {{ old('assessment_method') === 'interview' ? 'selected' : '' }}>Interview</option>
                                    <option value="demonstration" {{ old('assessment_method') === 'demonstration' ? 'selected' : '' }}>Demonstration</option>
                                    <option value="written_test" {{ old('assessment_method') === 'written_test' ? 'selected' : '' }}>Written Test</option>
                                    <option value="mixed" {{ old('assessment_method') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('assessment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="scheduleSection" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-900 text-white text-sm rounded-full mr-2">4</span>
                        Jadwal Assessment
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Scheduled Date -->
                        <div>
                            <label for="scheduled_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal *</label>
                            <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheduled_date') border-red-500 @enderror">
                            @error('scheduled_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Time -->
                        <div>
                            <label for="scheduled_time" class="block text-sm font-semibold text-gray-700 mb-2">Waktu</label>
                            <input type="time" id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheduled_time') border-red-500 @enderror">
                            @error('scheduled_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Venue -->
                        <div>
                            <label for="venue" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi/Venue</label>
                            <input type="text" id="venue" name="venue" value="{{ old('venue') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('venue') border-red-500 @enderror"
                                placeholder="Lokasi pelaksanaan assessment">
                            @error('venue')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Planned Duration -->
                        <div>
                            <label for="planned_duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Durasi (menit)</label>
                            <input type="number" id="planned_duration_minutes" name="planned_duration_minutes" value="{{ old('planned_duration_minutes', 120) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('planned_duration_minutes') border-red-500 @enderror">
                            @error('planned_duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="notesSection" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Catatan Tambahan</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea id="notes" name="notes" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror"
                                placeholder="Catatan umum">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" id="submitBtn" disabled
                            class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Buat Assessment
                        </button>

                        <a href="{{ route('admin.assessments.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Batal
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Catatan:</span> Field dengan tanda * wajib diisi.
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Nomor assessment akan di-generate otomatis (ASM-YYYY-####).
                        </p>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="hidden mt-4">
                        <div class="flex items-center justify-center space-x-2 text-blue-600">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm">Memuat data event...</span>
                        </div>
                    </div>
                </div>

                <!-- Event Summary Card -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6" id="eventSummary" style="display: none;">
                    <h4 class="font-semibold text-blue-900 mb-3">Ringkasan Event</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700">Event:</span>
                            <span class="font-medium text-blue-900" id="summaryEventName">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Skema:</span>
                            <span class="font-medium text-blue-900" id="summarySchemeName">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Lead Assessor:</span>
                            <span class="font-medium text-blue-900" id="summaryLeadAssessor">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Jumlah Asesi:</span>
                            <span class="font-medium text-blue-900" id="summaryAssesseeCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventSelect = document.getElementById('event_id');
    const eventDataSection = document.getElementById('eventDataSection');
    const assessmentDetailsSection = document.getElementById('assessmentDetailsSection');
    const scheduleSection = document.getElementById('scheduleSection');
    const notesSection = document.getElementById('notesSection');
    const eventSummary = document.getElementById('eventSummary');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const submitBtn = document.getElementById('submitBtn');

    const schemeDisplay = document.getElementById('schemeDisplay');
    const schemeIdInput = document.getElementById('scheme_id');
    const leadAssessorDisplay = document.getElementById('leadAssessorDisplay');
    const leadAssessorIdInput = document.getElementById('lead_assessor_id');
    const assesseeSelect = document.getElementById('assessee_id');
    const tukSelect = document.getElementById('tuk_id');
    const scheduledDateInput = document.getElementById('scheduled_date');
    const venueInput = document.getElementById('venue');
    const titleInput = document.getElementById('title');

    // Summary elements
    const summaryEventName = document.getElementById('summaryEventName');
    const summarySchemeName = document.getElementById('summarySchemeName');
    const summaryLeadAssessor = document.getElementById('summaryLeadAssessor');
    const summaryAssesseeCount = document.getElementById('summaryAssesseeCount');

    function showSections() {
        eventDataSection.style.display = 'block';
        assessmentDetailsSection.style.display = 'block';
        scheduleSection.style.display = 'block';
        notesSection.style.display = 'block';
        eventSummary.style.display = 'block';
    }

    function hideSections() {
        eventDataSection.style.display = 'none';
        assessmentDetailsSection.style.display = 'none';
        scheduleSection.style.display = 'none';
        notesSection.style.display = 'none';
        eventSummary.style.display = 'none';
    }

    function resetForm() {
        schemeDisplay.textContent = '-';
        schemeIdInput.value = '';
        leadAssessorDisplay.textContent = '-';
        leadAssessorIdInput.value = '';
        assesseeSelect.innerHTML = '<option value="">-- Pilih Asesi --</option>';
        submitBtn.disabled = true;
    }

    eventSelect.addEventListener('change', function() {
        const eventId = this.value;
        const selectedOption = this.options[this.selectedIndex];

        if (!eventId) {
            hideSections();
            resetForm();
            return;
        }

        // Show loading
        loadingIndicator.classList.remove('hidden');
        submitBtn.disabled = true;

        // Fetch event data
        fetch(`{{ url('admin/assessments/event') }}/${eventId}/data`)
            .then(response => response.json())
            .then(result => {
                loadingIndicator.classList.add('hidden');

                if (result.success) {
                    const data = result.data;

                    // Show all sections
                    showSections();

                    // Update scheme
                    if (data.scheme) {
                        schemeDisplay.textContent = `${data.scheme.name} (${data.scheme.code})`;
                        schemeIdInput.value = data.scheme.id;
                        summarySchemeName.textContent = data.scheme.name;
                    } else {
                        schemeDisplay.textContent = 'Tidak ada skema';
                        schemeIdInput.value = '';
                        summarySchemeName.textContent = '-';
                    }

                    // Update lead assessor
                    if (data.lead_assessor) {
                        leadAssessorDisplay.textContent = data.lead_assessor.name;
                        leadAssessorIdInput.value = data.lead_assessor.id;
                        summaryLeadAssessor.textContent = data.lead_assessor.name;
                    } else {
                        leadAssessorDisplay.textContent = 'Belum ada lead assessor';
                        leadAssessorIdInput.value = '';
                        summaryLeadAssessor.textContent = '-';
                    }

                    // Update assessees dropdown
                    assesseeSelect.innerHTML = '<option value="">-- Pilih Asesi --</option>';
                    if (data.assessees && data.assessees.length > 0) {
                        data.assessees.forEach(assessee => {
                            const option = document.createElement('option');
                            option.value = assessee.id;
                            option.textContent = `${assessee.full_name} - ${assessee.assessee_number || 'N/A'}`;
                            assesseeSelect.appendChild(option);
                        });
                        summaryAssesseeCount.textContent = data.assessees.length;
                    } else {
                        summaryAssesseeCount.textContent = '0';
                    }

                    // Update TUK dropdown with event TUKs and auto-select first one
                    if (data.tuks && data.tuks.length > 0) {
                        // Reset and add event TUKs first
                        const firstOption = tukSelect.querySelector('option[value=""]');
                        tukSelect.innerHTML = '';
                        tukSelect.appendChild(firstOption);

                        data.tuks.forEach((tuk, index) => {
                            const option = document.createElement('option');
                            option.value = tuk.id;
                            option.textContent = tuk.name + ' (Event)';
                            // Auto-select first TUK
                            if (index === 0) {
                                option.selected = true;
                            }
                            tukSelect.appendChild(option);
                        });
                    }

                    // Auto-fill scheduled date from event
                    if (data.start_date && !scheduledDateInput.value) {
                        scheduledDateInput.value = data.start_date;
                    }

                    // Auto-fill venue from event location
                    if (data.location && !venueInput.value) {
                        venueInput.value = data.location;
                    }

                    // Update summary
                    summaryEventName.textContent = selectedOption.textContent;

                    // Auto-generate title
                    if (!titleInput.value && data.scheme) {
                        titleInput.value = `Assessment ${data.scheme.name}`;
                    }

                    // Enable submit if we have required data
                    updateSubmitButton();
                }
            })
            .catch(error => {
                loadingIndicator.classList.add('hidden');
                console.error('Error fetching event data:', error);
                alert('Gagal memuat data event. Silakan coba lagi.');
            });
    });

    // Update submit button state
    function updateSubmitButton() {
        const hasEvent = eventSelect.value !== '';
        const hasScheme = schemeIdInput.value !== '';
        const hasAssessor = leadAssessorIdInput.value !== '';

        submitBtn.disabled = !(hasEvent && hasScheme && hasAssessor);
    }

    // Listen for assessee selection
    assesseeSelect.addEventListener('change', updateSubmitButton);

    // If there's old event_id, trigger change to load data
    @if(old('event_id'))
        eventSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endsection
