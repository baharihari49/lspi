@extends('layouts.admin')

@section('title', 'APL-02 Assessor Reviews')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'APL-02 Assessor Reviews')
@section('page_description', 'Manage APL-02 portfolio assessor reviews and evaluations')

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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Assessor Reviews</h2>
                    <p class="text-sm text-gray-600">Total: {{ $reviews->total() }} reviews</p>
                </div>
                <a href="{{ route('admin.apl02.reviews.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Create Review</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.apl02.reviews.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by review number..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'review_type', 'status', 'decision', 'assessor_id', 'is_final']))
                        <a href="{{ route('admin.apl02.reviews.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                    <select name="review_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Review Types</option>
                        <option value="initial_review" {{ request('review_type') === 'initial_review' ? 'selected' : '' }}>Initial Review</option>
                        <option value="verification" {{ request('review_type') === 'verification' ? 'selected' : '' }}>Verification</option>
                        <option value="validation" {{ request('review_type') === 'validation' ? 'selected' : '' }}>Validation</option>
                        <option value="final_assessment" {{ request('review_type') === 'final_assessment' ? 'selected' : '' }}>Final Assessment</option>
                        <option value="re_assessment" {{ request('review_type') === 're_assessment' ? 'selected' : '' }}>Re-assessment</option>
                    </select>

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

                    <select name="assessor_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Assessors</option>
                        @foreach($assessors as $assessor)
                            <option value="{{ $assessor->id }}" {{ request('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                {{ $assessor->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="is_final" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Reviews</option>
                        <option value="1" {{ request('is_final') === '1' ? 'selected' : '' }}>Final Reviews Only</option>
                        <option value="0" {{ request('is_final') === '0' ? 'selected' : '' }}>Non-Final Reviews</option>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Decision</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
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
                                <div class="text-sm text-gray-900">{{ $review->assessor->name }}</div>
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
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.apl02.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    @if($review->status === 'draft' || $review->status === 'in_progress')
                                        <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="text-green-600 hover:text-green-800" title="Conduct Review">
                                            <span class="material-symbols-outlined text-xl">rate_review</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.apl02.reviews.edit', $review) }}" class="text-gray-600 hover:text-gray-800" title="Edit">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('admin.apl02.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">rate_review</span>
                                <p class="font-medium">No reviews found</p>
                                <p class="text-sm">Try adjusting your search or filters</p>
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
