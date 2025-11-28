{{-- Assessor Dashboard --}}

{{-- Review Statistics --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    {{-- APL-01 Review Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-indigo-600 text-2xl">assignment_ind</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Review APL-01</h3>
                <p class="text-xs text-gray-500">{{ $review_stats['total_apl01_reviews'] ?? 0 }} total</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 text-center text-xs mb-3">
            <div class="p-2 bg-yellow-50 rounded-lg">
                <p class="font-bold text-yellow-700">{{ $review_stats['pending_apl01_reviews'] ?? 0 }}</p>
                <p class="text-yellow-600">Pending</p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg">
                <p class="font-bold text-green-700">{{ $review_stats['completed_apl01_reviews'] ?? 0 }}</p>
                <p class="text-green-600">Completed</p>
            </div>
        </div>
        <a href="{{ route('admin.apl01-reviews.my-reviews') }}" class="flex items-center justify-center gap-1 text-sm text-indigo-600 hover:text-indigo-800">
            Lihat Review Saya <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    {{-- APL-02 Review Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-violet-600 text-2xl">person_check</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Review APL-02</h3>
                <p class="text-xs text-gray-500">{{ $review_stats['total_apl02_reviews'] ?? 0 }} total</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 text-center text-xs mb-3">
            <div class="p-2 bg-yellow-50 rounded-lg">
                <p class="font-bold text-yellow-700">{{ $review_stats['pending_apl02_reviews'] ?? 0 }}</p>
                <p class="text-yellow-600">Pending</p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg">
                <p class="font-bold text-green-700">{{ $review_stats['completed_apl02_reviews'] ?? 0 }}</p>
                <p class="text-green-600">Completed</p>
            </div>
        </div>
        <a href="{{ route('admin.apl02.reviews.my-reviews') }}" class="flex items-center justify-center gap-1 text-sm text-violet-600 hover:text-violet-800">
            Lihat Review Saya <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    {{-- Assessment Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600 text-2xl">assignment</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Asesmen Saya</h3>
                <p class="text-xs text-gray-500">{{ $review_stats['total_assessments'] ?? 0 }} total</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 text-center text-xs mb-3">
            <div class="p-2 bg-blue-50 rounded-lg">
                <p class="font-bold text-blue-700">{{ $review_stats['scheduled_assessments'] ?? 0 }}</p>
                <p class="text-blue-600">Scheduled</p>
            </div>
            <div class="p-2 bg-yellow-50 rounded-lg">
                <p class="font-bold text-yellow-700">{{ $review_stats['in_progress_assessments'] ?? 0 }}</p>
                <p class="text-yellow-600">In Progress</p>
            </div>
        </div>
        <a href="{{ route('admin.assessments.index') }}" class="flex items-center justify-center gap-1 text-sm text-orange-600 hover:text-orange-800">
            Lihat Asesmen <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
</div>

{{-- Pending Alert --}}
@php
    $totalPending = ($review_stats['pending_apl01_reviews'] ?? 0) +
                   ($review_stats['pending_apl02_reviews'] ?? 0) +
                   ($review_stats['scheduled_assessments'] ?? 0);
@endphp
@if($totalPending > 0)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-amber-600 text-2xl">pending_actions</span>
        <div>
            <h3 class="font-semibold text-amber-800">{{ $totalPending }} tugas menunggu perhatian Anda</h3>
            <p class="text-sm text-amber-700">Anda memiliki review dan asesmen yang perlu diselesaikan.</p>
        </div>
    </div>
</div>
@endif

{{-- Lists Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Pending APL-01 Reviews --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Review APL-01 Pending</h2>
            <a href="{{ route('admin.apl01-reviews.my-reviews') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pending_apl01_reviews ?? [] as $review)
                <a href="{{ route('admin.apl01-reviews.review', $review) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-indigo-600">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $review->form->assessee->full_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $review->form->scheme->name ?? 'N/A' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                            {{ $review->decision_label ?? ucfirst($review->decision ?? 'Pending') }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">check_circle</span>
                    <p class="text-sm">Tidak ada review pending</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pending APL-02 Reviews --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Review APL-02 Pending</h2>
            <a href="{{ route('admin.apl02.reviews.my-reviews') }}" class="text-sm text-violet-600 hover:text-violet-800">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pending_apl02_reviews ?? [] as $review)
                <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-violet-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-violet-600">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $review->apl02Unit->assessee->full_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $review->apl02Unit->unit_code ?? 'N/A' }} - {{ $review->apl02Unit->unit_title ?? 'N/A' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                            {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">check_circle</span>
                    <p class="text-sm">Tidak ada review pending</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Upcoming Assessments --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="font-bold text-gray-900">Jadwal Asesmen Mendatang</h2>
        <a href="{{ route('admin.assessments.index') }}" class="text-sm text-orange-600 hover:text-orange-800">Lihat Semua →</a>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($upcoming_assessments ?? [] as $assessment)
            <a href="{{ route('admin.assessments.show', $assessment) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                <div class="text-center flex-shrink-0 w-14">
                    <p class="text-2xl font-bold text-orange-600">{{ $assessment->scheduled_date?->format('d') ?? '-' }}</p>
                    <p class="text-xs text-gray-500 uppercase">{{ $assessment->scheduled_date?->format('M') ?? '-' }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $assessment->assessee->full_name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $assessment->scheme->name ?? 'N/A' }} • {{ $assessment->tuk->name ?? 'TBA' }}</p>
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
