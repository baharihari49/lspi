@extends('layouts.admin')

@section('title', 'APL-01 Reviews')

@php
    $active = 'apl01-reviews';
@endphp

@section('page_title', 'APL-01 Reviews')
@section('page_description', 'Manage all APL-01 form reviews')

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

    <!-- Pending Submissions Alert -->
    @if($pendingSubmissions->count() > 0)
        <div class="mb-6 bg-orange-50 border border-orange-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">pending_actions</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-orange-900 mb-2">
                        {{ $pendingSubmissions->count() }} Form Menunggu Diterima
                    </h3>
                    <p class="text-sm text-orange-700 mb-4">Form-form berikut sudah disubmit oleh assessee dan menunggu untuk diterima review.</p>

                    <div class="space-y-3">
                        @foreach($pendingSubmissions as $form)
                            <div class="flex items-center justify-between bg-white rounded-lg p-4 border border-orange-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($form->assessee->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $form->form_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $form->assessee->full_name }} - {{ $form->scheme->name }}</p>
                                        <p class="text-xs text-gray-500">Submitted: {{ $form->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.apl01.show', $form) }}" class="px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition text-sm font-medium">
                                        View
                                    </a>
                                    <form action="{{ route('admin.apl01.accept-review', $form) }}" method="POST" class="inline" onsubmit="return confirm('Terima form ini untuk review?')">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-semibold flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Accept
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">All Reviews</h2>
                    <p class="text-sm text-gray-600">Total: {{ $reviews->total() }} reviews</p>
                </div>
                <a href="{{ route('admin.apl01-reviews.my-reviews') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">assignment_ind</span>
                    <span>My Reviews</span>
                </a>
            </div>

            <!-- Filter Box -->
            <form method="GET" action="{{ route('admin.apl01-reviews.index') }}" class="space-y-3">
                <div class="flex gap-2 flex-wrap">
                    <select name="decision" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Decisions</option>
                        <option value="pending" {{ request('decision') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('decision') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('decision') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="approved_with_notes" {{ request('decision') === 'approved_with_notes' ? 'selected' : '' }}>Approved with Notes</option>
                        <option value="returned" {{ request('decision') === 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="forwarded" {{ request('decision') === 'forwarded' ? 'selected' : '' }}>Forwarded</option>
                        <option value="on_hold" {{ request('decision') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    </select>

                    <select name="reviewer_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Reviewers</option>
                        @foreach($reviewers as $reviewer)
                            <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                {{ $reviewer->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="review_level" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Levels</option>
                        <option value="1" {{ request('review_level') === '1' ? 'selected' : '' }}>Level 1</option>
                        <option value="2" {{ request('review_level') === '2' ? 'selected' : '' }}>Level 2</option>
                        <option value="3" {{ request('review_level') === '3' ? 'selected' : '' }}>Level 3</option>
                    </select>

                    <label class="flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="current_only" value="1" {{ request('current_only') ? 'checked' : '' }} class="mr-2">
                        Current Only
                    </label>

                    <label class="flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }} class="mr-2">
                        Overdue Only
                    </label>

                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Apply Filters
                    </button>

                    @if(request()->hasAny(['decision', 'reviewer_id', 'review_level', 'current_only', 'overdue']))
                        <a href="{{ route('admin.apl01-reviews.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Form / Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Review Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reviewer</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Decision</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $review->form->form_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $review->form->assessee->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->form->scheme->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $review->review_level_name ?? 'Level ' . $review->review_level }}</p>
                                    <div class="flex gap-1 mt-1">
                                        @if($review->is_current)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">Current</span>
                                        @endif
                                        @if($review->is_final)
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded">Final</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $review->reviewer->name }}</p>
                                @if($review->reviewer_role)
                                    <p class="text-xs text-gray-500">{{ $review->reviewer_role }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $review->decision_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($review->score)
                                    <span class="font-semibold text-gray-900">{{ $review->score }}</span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($review->completed_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @elseif($review->started_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        In Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Not Started
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($review->deadline)
                                    <div>
                                        <p class="text-gray-900">{{ $review->deadline->format('d M Y') }}</p>
                                        @if($review->is_overdue && !$review->completed_at)
                                            <p class="text-xs text-red-600 font-semibold">Overdue</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.apl01-reviews.show', $review) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    @if($review->decision === 'pending' && !$review->completed_at)
                                        <a href="{{ route('admin.apl01-reviews.review', $review) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Review">
                                            <span class="material-symbols-outlined text-xl">rate_review</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-3">rate_review</span>
                                    <p class="text-gray-500 font-medium">No reviews found</p>
                                    <p class="text-gray-400 text-sm mt-1">Reviews will appear here when forms are submitted</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
