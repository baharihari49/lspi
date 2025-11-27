@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@php
    $active = 'dashboard';
@endphp

@section('page_title', 'Dashboard')
@section('page_description', 'Selamat datang di panel admin LSP-PIE')

@section('content')
    @if($user_role === 'admin')
        {{-- ADMIN DASHBOARD --}}

        {{-- Pending Actions Alert --}}
        @php
            $totalPending = ($pending_actions['apl01_to_review'] ?? 0) +
                           ($pending_actions['apl02_to_review'] ?? 0) +
                           ($pending_actions['results_to_approve'] ?? 0) +
                           ($pending_actions['certificates_to_issue'] ?? 0) +
                           ($pending_actions['payments_to_verify'] ?? 0);
        @endphp
        @if($totalPending > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600 text-2xl">pending_actions</span>
                <div class="flex-1">
                    <h3 class="font-semibold text-amber-800">{{ $totalPending }} Tindakan Menunggu</h3>
                    <div class="flex flex-wrap gap-3 mt-1 text-sm text-amber-700">
                        @if($pending_actions['apl01_to_review'] > 0)
                            <a href="{{ route('admin.apl01.index', ['status' => 'submitted']) }}" class="hover:underline">{{ $pending_actions['apl01_to_review'] }} APL-01</a>
                        @endif
                        @if($pending_actions['apl02_to_review'] > 0)
                            <a href="{{ route('admin.apl02.units.index', ['status' => 'submitted']) }}" class="hover:underline">{{ $pending_actions['apl02_to_review'] }} APL-02</a>
                        @endif
                        @if($pending_actions['results_to_approve'] > 0)
                            <a href="{{ route('admin.result-approval.index') }}" class="hover:underline">{{ $pending_actions['results_to_approve'] }} Results</a>
                        @endif
                        @if($pending_actions['certificates_to_issue'] > 0)
                            <a href="{{ route('admin.certificates.index', ['status' => 'pending']) }}" class="hover:underline">{{ $pending_actions['certificates_to_issue'] }} Certificates</a>
                        @endif
                        @if($pending_actions['payments_to_verify'] > 0)
                            <a href="{{ route('admin.payments.index', ['status' => 'pending_verification']) }}" class="hover:underline">{{ $pending_actions['payments_to_verify'] }} Payments</a>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.certification-flow.dashboard') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    Lihat Flow
                </a>
            </div>
        </div>
        @endif

        {{-- Overview Statistics Row 1: Master Data --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <a href="{{ route('admin.schemes.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">verified</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_schemes'] }}</span>
                </div>
                <p class="text-xs text-gray-500">Skema Sertifikasi</p>
                <p class="text-xs text-green-600">{{ $master_data_stats['active_schemes'] }} aktif</p>
            </a>

            <a href="{{ route('admin.events.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600">event</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_events'] }}</span>
                </div>
                <p class="text-xs text-gray-500">Events</p>
                <p class="text-xs text-green-600">{{ $master_data_stats['active_events'] }} aktif</p>
            </a>

            <a href="{{ route('admin.assessors.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600">badge</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_assessors'] }}</span>
                </div>
                <p class="text-xs text-gray-500">Assessors</p>
                <p class="text-xs text-green-600">{{ $master_data_stats['active_assessors'] }} aktif</p>
            </a>

            <a href="{{ route('admin.assessees.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-cyan-600">school</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_assessees'] }}</span>
                </div>
                <p class="text-xs text-gray-500">Assessees</p>
            </a>

            <a href="{{ route('admin.tuk.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-teal-600">location_city</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_tuk'] }}</span>
                </div>
                <p class="text-xs text-gray-500">TUK</p>
                <p class="text-xs text-green-600">{{ $master_data_stats['active_tuk'] }} aktif</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-600">people</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $master_data_stats['total_users'] }}</span>
                </div>
                <p class="text-xs text-gray-500">Total Users</p>
            </a>
        </div>

        {{-- Certification Flow Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- APL-01 --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">APL-01</h3>
                            <p class="text-xs text-gray-500">Pendaftaran</p>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $certification_stats['total_apl01'] }}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <p class="font-bold text-yellow-700">{{ $certification_stats['apl01_pending'] }}</p>
                        <p class="text-yellow-600">Pending</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <p class="font-bold text-blue-700">{{ $certification_stats['apl01_in_review'] }}</p>
                        <p class="text-blue-600">Review</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <p class="font-bold text-green-700">{{ $certification_stats['apl01_approved'] }}</p>
                        <p class="text-green-600">Approved</p>
                    </div>
                </div>
                <a href="{{ route('admin.apl01.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                    Lihat semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- APL-02 --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-600 text-2xl">folder_open</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">APL-02</h3>
                            <p class="text-xs text-gray-500">Portfolio</p>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $certification_stats['total_apl02_units'] }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <p class="font-bold text-blue-700">{{ $certification_stats['apl02_in_progress'] }}</p>
                        <p class="text-blue-600">In Progress</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <p class="font-bold text-green-700">{{ $certification_stats['apl02_completed'] }}</p>
                        <p class="text-green-600">Completed</p>
                    </div>
                </div>
                <a href="{{ route('admin.apl02.units.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-purple-600 hover:text-purple-800">
                    Lihat semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- Assessments --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-orange-600 text-2xl">assignment</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Assessments</h3>
                            <p class="text-xs text-gray-500">Ujian</p>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $certification_stats['total_assessments'] }}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <p class="font-bold text-yellow-700">{{ $certification_stats['assessments_scheduled'] }}</p>
                        <p class="text-yellow-600">Scheduled</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <p class="font-bold text-blue-700">{{ $certification_stats['assessments_in_progress'] }}</p>
                        <p class="text-blue-600">Progress</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <p class="font-bold text-green-700">{{ $certification_stats['assessments_completed'] }}</p>
                        <p class="text-green-600">Done</p>
                    </div>
                </div>
                <a href="{{ route('admin.assessments.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-orange-600 hover:text-orange-800">
                    Lihat semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- Certificates --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-600 text-2xl">workspace_premium</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Sertifikat</h3>
                            <p class="text-xs text-gray-500">Issued</p>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $certification_stats['total_certificates'] }}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <p class="font-bold text-green-700">{{ $certification_stats['certificates_active'] }}</p>
                        <p class="text-green-600">Active</p>
                    </div>
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <p class="font-bold text-yellow-700">{{ $certification_stats['certificates_pending'] }}</p>
                        <p class="text-yellow-600">Pending</p>
                    </div>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <p class="font-bold text-red-700">{{ $certification_stats['certificates_expiring_soon'] }}</p>
                        <p class="text-red-600">Expiring</p>
                    </div>
                </div>
                <a href="{{ route('admin.certificates.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-green-600 hover:text-green-800">
                    Lihat semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Payment & Reviews Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Payment Stats --}}
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl shadow-sm p-5 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">payments</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Keuangan</h3>
                        <p class="text-xs text-emerald-100">{{ $payment_stats['total_payments'] }} transaksi</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-emerald-100 text-xs mb-1">Total Revenue</p>
                        <p class="text-xl font-bold">Rp {{ number_format($payment_stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-xs mb-1">Pending</p>
                        <p class="text-xl font-bold">Rp {{ number_format($payment_stats['pending_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="mt-4 flex items-center justify-center gap-1 text-sm bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    Kelola Pembayaran <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- APL-01 Reviews --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600 text-2xl">rate_review</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Review APL-01</h3>
                        <p class="text-xs text-gray-500">{{ $certification_stats['total_apl01_reviews'] }} total reviews</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm text-yellow-700">Pending Reviews</span>
                    <span class="text-xl font-bold text-yellow-700">{{ $certification_stats['apl01_reviews_pending'] }}</span>
                </div>
                <a href="{{ route('admin.apl01-reviews.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-indigo-600 hover:text-indigo-800">
                    Kelola Reviews <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- APL-02 Reviews --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-violet-600 text-2xl">fact_check</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Review APL-02</h3>
                        <p class="text-xs text-gray-500">{{ $certification_stats['total_apl02_reviews'] }} total reviews</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm text-yellow-700">Pending Reviews</span>
                    <span class="text-xl font-bold text-yellow-700">{{ $certification_stats['apl02_reviews_pending'] }}</span>
                </div>
                <a href="{{ route('admin.apl02.reviews.index') }}" class="mt-3 flex items-center justify-center gap-1 text-sm text-violet-600 hover:text-violet-800">
                    Kelola Reviews <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Recent Items Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Recent APL-01 --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">APL-01 Terbaru</h2>
                    <a href="{{ route('admin.apl01.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_apl01 as $apl01)
                        <a href="{{ route('admin.apl01.show', $apl01) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-600">person</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $apl01->assessee->full_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $apl01->scheme->name ?? 'N/A' }}</p>
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
                            <p class="text-sm">Belum ada data APL-01</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Events --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Event Mendatang</h2>
                    <a href="{{ route('admin.events.index') }}" class="text-sm text-purple-600 hover:text-purple-800">Lihat Semua →</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($upcoming_events as $event)
                        <a href="{{ route('admin.events.show', $event) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                            <div class="text-center flex-shrink-0 w-14">
                                <p class="text-2xl font-bold text-purple-600">{{ $event->start_date?->format('d') ?? '-' }}</p>
                                <p class="text-xs text-gray-500 uppercase">{{ $event->start_date?->format('M') ?? '-' }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $event->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $event->scheme->name ?? 'N/A' }} • {{ $event->location ?? 'TBA' }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($event->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Inactive</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-500">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event</span>
                            <p class="text-sm">Belum ada event mendatang</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Content Stats & Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- News Stats --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600">article</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">{{ $stats['total_news'] }}</span>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Berita</h3>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="text-green-600 font-medium">{{ $stats['published_news'] }} Published</span>
                    <span class="text-yellow-600 font-medium">{{ $stats['draft_news'] }} Draft</span>
                </div>
                <a href="{{ route('admin.news.index') }}" class="mt-3 flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                    Kelola Berita <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- Announcements Stats --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-600">campaign</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">{{ $stats['total_announcements'] }}</span>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Pengumuman</h3>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="text-green-600 font-medium">{{ $stats['published_announcements'] }} Published</span>
                    <span class="text-yellow-600 font-medium">{{ $stats['draft_announcements'] }} Draft</span>
                </div>
                <a href="{{ route('admin.announcements.index') }}" class="mt-3 flex items-center gap-1 text-sm text-purple-600 hover:text-purple-800">
                    Kelola Pengumuman <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-xl shadow-sm p-5 text-white">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined">add_circle</span>
                    </div>
                </div>
                <h3 class="text-sm font-semibold mb-3">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.events.create') }}" class="flex items-center gap-2 text-sm hover:bg-white/10 px-3 py-2 rounded-lg transition">
                        <span class="material-symbols-outlined text-lg">add</span>
                        <span>Buat Event Baru</span>
                    </a>
                    <a href="{{ route('admin.news.create') }}" class="flex items-center gap-2 text-sm hover:bg-white/10 px-3 py-2 rounded-lg transition">
                        <span class="material-symbols-outlined text-lg">add</span>
                        <span>Tambah Berita</span>
                    </a>
                    <a href="{{ route('admin.announcements.create') }}" class="flex items-center gap-2 text-sm hover:bg-white/10 px-3 py-2 rounded-lg transition">
                        <span class="material-symbols-outlined text-lg">add</span>
                        <span>Tambah Pengumuman</span>
                    </a>
                </div>
            </div>
        </div>

    @elseif($user_role === 'assessor')
        {{-- ASSESSOR DASHBOARD --}}
        @include('admin.dashboard.assessor')

    @elseif($user_role === 'assessee')
        {{-- ASSESSEE DASHBOARD --}}
        @include('admin.dashboard.assessee')

    @else
        {{-- DEFAULT DASHBOARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">dashboard</span>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Selamat Datang di LSP-PIE</h2>
            <p class="text-gray-500">Dashboard Anda sedang disiapkan.</p>
        </div>
    @endif
@endsection
