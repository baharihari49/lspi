{{-- Assessee Dashboard --}}

{{-- My Certification Progress --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600">description</span>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $my_stats['total_apl01'] ?? 0 }}</span>
        </div>
        <p class="text-xs text-gray-500">APL-01 Saya</p>
        <p class="text-xs text-green-600">{{ $my_stats['apl01_approved'] ?? 0 }} disetujui</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-600">folder_open</span>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $my_stats['total_apl02_units'] ?? 0 }}</span>
        </div>
        <p class="text-xs text-gray-500">APL-02 Saya</p>
        <p class="text-xs text-green-600">{{ $my_stats['apl02_completed'] ?? 0 }} selesai</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600">assignment</span>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $my_stats['total_assessments'] ?? 0 }}</span>
        </div>
        <p class="text-xs text-gray-500">Asesmen Saya</p>
        <p class="text-xs text-blue-600">{{ $my_stats['assessments_scheduled'] ?? 0 }} terjadwal</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">workspace_premium</span>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $my_stats['total_certificates'] ?? 0 }}</span>
        </div>
        <p class="text-xs text-gray-500">Sertifikat Saya</p>
        <p class="text-xs text-green-600">{{ $my_stats['active_certificates'] ?? 0 }} aktif</p>
    </div>
</div>

{{-- Quick Actions for Assessee --}}
<div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl shadow-sm p-5 text-white mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold mb-1">Mulai Perjalanan Sertifikasi Anda</h2>
            <p class="text-blue-200 text-sm">Daftarkan diri Anda ke event sertifikasi yang tersedia.</p>
        </div>
        <a href="{{ route('admin.available-events.index') }}" class="inline-flex items-center gap-2 bg-white text-blue-900 px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-50 transition">
            <span class="material-symbols-outlined">event_available</span>
            Lihat Event Tersedia
        </a>
    </div>
</div>

{{-- Pending Payments Alert --}}
@if(isset($pending_payments) && $pending_payments->count() > 0)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-amber-600 text-2xl">payments</span>
        <div class="flex-1">
            <h3 class="font-semibold text-amber-800">{{ $pending_payments->count() }} Pembayaran Menunggu</h3>
            <p class="text-sm text-amber-700">Anda memiliki pembayaran yang perlu diselesaikan.</p>
        </div>
        <a href="{{ route('admin.my-payments.index') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
            Bayar Sekarang
        </a>
    </div>
</div>
@endif

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- My APL-01 Submissions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">APL-01 Saya</h2>
            <a href="{{ route('admin.my-apl01.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($my_apl01_forms ?? [] as $apl01)
                <a href="{{ route('admin.my-apl01.show', $apl01) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-blue-600">description</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $apl01->scheme->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $apl01->event->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'submitted' => 'bg-yellow-100 text-yellow-700',
                                'in_review' => 'bg-blue-100 text-blue-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$apl01->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $apl01->status)) }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $apl01->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">description</span>
                    <p class="text-sm mb-2">Belum ada APL-01</p>
                    <a href="{{ route('admin.available-events.index') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Daftar Event
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- My Upcoming Assessments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Jadwal Asesmen Saya</h2>
            <a href="{{ route('admin.my-assessments.index') }}" class="text-sm text-orange-600 hover:text-orange-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($my_upcoming_assessments ?? [] as $assessment)
                <a href="{{ route('admin.my-assessments.show', $assessment) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="text-center flex-shrink-0 w-14">
                        <p class="text-2xl font-bold text-orange-600">{{ $assessment->scheduled_date?->format('d') ?? '-' }}</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $assessment->scheduled_date?->format('M') ?? '-' }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $assessment->scheme->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $assessment->tuk->name ?? 'TBA' }} • Assessor: {{ $assessment->assessor->name ?? 'TBA' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-700',
                                'in_progress' => 'bg-yellow-100 text-yellow-700',
                                'completed' => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$assessment->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event</span>
                    <p class="text-sm">Belum ada jadwal asesmen</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Certificates & Available Events --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- My Certificates --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Sertifikat Saya</h2>
            <a href="{{ route('admin.my-certificates.index') }}" class="text-sm text-green-600 hover:text-green-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($my_certificates ?? [] as $certificate)
                <a href="{{ route('admin.my-certificates.show', $certificate) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-green-600">workspace_premium</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $certificate->scheme->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $certificate->certificate_number }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @php
                            $certStatusColors = [
                                'active' => 'bg-green-100 text-green-700',
                                'expired' => 'bg-red-100 text-red-700',
                                'suspended' => 'bg-yellow-100 text-yellow-700',
                                'revoked' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $certStatusColors[$certificate->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($certificate->status) }}
                        </span>
                        @if($certificate->valid_until)
                            <p class="text-xs text-gray-400 mt-1">s/d {{ $certificate->valid_until->format('d M Y') }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">workspace_premium</span>
                    <p class="text-sm">Belum ada sertifikat</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Available Events --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Event Sertifikasi Tersedia</h2>
            <a href="{{ route('admin.available-events.index') }}" class="text-sm text-purple-600 hover:text-purple-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($available_events ?? [] as $event)
                <a href="{{ route('admin.available-events.show', $event) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="text-center flex-shrink-0 w-14">
                        <p class="text-2xl font-bold text-purple-600">{{ $event->start_date?->format('d') ?? '-' }}</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $event->start_date?->format('M') ?? '-' }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $event->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $event->scheme->name ?? 'N/A' }} • {{ $event->location ?? 'TBA' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 flex-shrink-0">
                        Open
                    </span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event</span>
                    <p class="text-sm">Belum ada event tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- News & Announcements --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent News --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-900">Berita Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recent_news ?? [] as $news)
                <div class="px-5 py-3">
                    <h3 class="font-medium text-gray-900 truncate mb-1">{{ $news->title }}</h3>
                    <p class="text-xs text-gray-500">{{ $news->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-gray-500">
                    <p class="text-sm">Belum ada berita</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Announcements --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-900">Pengumuman Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recent_announcements ?? [] as $announcement)
                <div class="px-5 py-3">
                    <h3 class="font-medium text-gray-900 truncate mb-1">{{ $announcement->title }}</h3>
                    <p class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-gray-500">
                    <p class="text-sm">Belum ada pengumuman</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
