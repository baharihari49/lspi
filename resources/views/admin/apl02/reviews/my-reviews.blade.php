@extends('layouts.admin')

@section('title', 'My APL-02 Reviews')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'My APL-02 Reviews')
@section('page_description', 'Reviews assigned to you for APL-02 portfolio assessment')

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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalReviews = $reviews->total();
            $draftReviews = \App\Models\Apl02AssessorReview::where('assessor_id', auth()->id())->where('status', 'draft')->count();
            $inProgressReviews = \App\Models\Apl02AssessorReview::where('assessor_id', auth()->id())->where('status', 'in_progress')->count();
            $completedReviews = \App\Models\Apl02AssessorReview::where('assessor_id', auth()->id())->whereIn('status', ['completed', 'submitted', 'approved'])->count();
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Reviews</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalReviews }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 text-2xl">rate_review</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $draftReviews }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <span class="material-symbols-outlined text-gray-600 text-2xl">edit_note</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $inProgressReviews }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 text-2xl">pending</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $completedReviews }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">My Reviews</h2>
                    <p class="text-sm text-gray-600">Reviews assigned to me: {{ $reviews->total() }}</p>
                </div>
                <a href="{{ route('admin.apl02.reviews.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">list</span>
                    <span>All Reviews</span>
                </a>
            </div>

            <!-- Filter Box -->
            <form method="GET" action="{{ route('admin.apl02.reviews.my-reviews') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'decision']))
                        <a href="{{ route('admin.apl02.reviews.my-reviews') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-2 gap-2">
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="revision_required" {{ request('status') === 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                    </select>

                    <select name="decision" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Decisions</option>
                        <option value="pending" {{ request('decision') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="competent" {{ request('decision') === 'competent' ? 'selected' : '' }}>Competent</option>
                        <option value="not_yet_competent" {{ request('decision') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                        <option value="requires_more_evidence" {{ request('decision') === 'requires_more_evidence' ? 'selected' : '' }}>Requires More Evidence</option>
                        <option value="requires_demonstration" {{ request('decision') === 'requires_demonstration' ? 'selected' : '' }}>Requires Demonstration</option>
                        <option value="deferred" {{ request('decision') === 'deferred' ? 'selected' : '' }}>Deferred</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Review</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit & Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Decision</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $review->review_number }}</div>
                                @if($review->is_final)
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                        FINAL
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $review->apl02Unit->unit_code }}</div>
                                <div class="text-xs text-gray-600">{{ $review->apl02Unit->assessee->full_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'initial_review' => 'bg-blue-100 text-blue-800',
                                        'verification' => 'bg-green-100 text-green-800',
                                        'validation' => 'bg-purple-100 text-purple-800',
                                        'final_assessment' => 'bg-orange-100 text-orange-800',
                                        're_assessment' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $typeColors[$review->review_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $review->review_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'submitted' => 'bg-indigo-100 text-indigo-800',
                                        'approved' => 'bg-purple-100 text-purple-800',
                                        'revision_required' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$review->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $decisionColors = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'competent' => 'bg-green-100 text-green-800',
                                        'not_yet_competent' => 'bg-red-100 text-red-800',
                                        'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                        'requires_demonstration' => 'bg-yellow-100 text-yellow-800',
                                        'deferred' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($review->overall_score)
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($review->overall_score, 1) }}</div>
                                    <div class="text-xs text-gray-600">Overall</div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($review->deadline)
                                    @php
                                        $isOverdue = $review->deadline->isPast() && !in_array($review->status, ['completed', 'submitted', 'approved']);
                                    @endphp
                                    <div class="text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                        {{ $review->deadline->format('d M Y') }}
                                    </div>
                                    @if($isOverdue)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded">
                                            Overdue
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if(in_array($review->status, ['draft', 'in_progress']))
                                        <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="text-green-600 hover:text-green-800" title="Conduct Review">
                                            <span class="material-symbols-outlined text-xl">rate_review</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.apl02.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">rate_review</span>
                                <p class="font-medium">No reviews assigned to you yet</p>
                                <p class="text-sm">Reviews will appear here when they are assigned to you</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
