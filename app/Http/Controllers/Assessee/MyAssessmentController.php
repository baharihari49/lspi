<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MyAssessmentController extends Controller
{
    /**
     * Get the current user's assessee record
     */
    private function getAssessee()
    {
        $user = auth()->user();
        return $user->assessee ?? null;
    }

    /**
     * Display a listing of user's assessments.
     */
    public function index(Request $request)
    {
        $assessee = $this->getAssessee();
        $assesseeId = $assessee?->id;

        // If no assessee profile, show empty state
        if (!$assesseeId) {
            $assessments = new LengthAwarePaginator([], 0, 10);
            $stats = ['total' => 0, 'scheduled' => 0, 'completed' => 0, 'competent' => 0, 'not_yet_competent' => 0];
            return view('assessee.my-assessments.index', compact('assessments', 'stats'));
        }

        // Hanya tampilkan assessment yang sudah dikonfirmasi admin (bukan pending_confirmation)
        $query = Assessment::where('assessee_id', $assesseeId)
            ->where('status', '!=', 'pending_confirmation');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('overall_result', $request->result);
        }

        $assessments = $query->with(['scheme', 'event', 'tuk', 'leadAssessor'])
            ->orderBy('scheduled_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Statistics (exclude pending_confirmation)
        $confirmedQuery = Assessment::where('assessee_id', $assesseeId)
            ->where('status', '!=', 'pending_confirmation');

        $stats = [
            'total' => (clone $confirmedQuery)->count(),
            'scheduled' => (clone $confirmedQuery)->where('status', 'scheduled')->count(),
            'completed' => (clone $confirmedQuery)->where('status', 'completed')->count(),
            'competent' => (clone $confirmedQuery)->where('overall_result', 'competent')->count(),
            'not_yet_competent' => (clone $confirmedQuery)->where('overall_result', 'not_yet_competent')->count(),
        ];

        return view('assessee.my-assessments.index', compact('assessments', 'stats'));
    }

    /**
     * Display the specified assessment.
     */
    public function show(Assessment $assessment)
    {
        $assessee = $this->getAssessee();

        if (!$assessee || $assessment->assessee_id !== $assessee->id) {
            abort(403, 'Anda tidak memiliki akses ke asesmen ini.');
        }

        // Assessee tidak bisa melihat assessment yang masih pending_confirmation
        if ($assessment->status === 'pending_confirmation') {
            abort(403, 'Asesmen ini masih menunggu konfirmasi admin.');
        }

        $assessment->load([
            'scheme',
            'event.tuks.tuk',
            'tuk',
            'leadAssessor',
            'assessmentUnits',
            'results',
        ]);

        return view('assessee.my-assessments.show', compact('assessment'));
    }
}
