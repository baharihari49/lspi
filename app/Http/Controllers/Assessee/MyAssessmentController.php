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

        $query = Assessment::where('assessee_id', $assesseeId);

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

        // Statistics
        $stats = [
            'total' => Assessment::where('assessee_id', $assesseeId)->count(),
            'scheduled' => Assessment::where('assessee_id', $assesseeId)->where('status', 'scheduled')->count(),
            'completed' => Assessment::where('assessee_id', $assesseeId)->where('status', 'completed')->count(),
            'competent' => Assessment::where('assessee_id', $assesseeId)->where('overall_result', 'competent')->count(),
            'not_yet_competent' => Assessment::where('assessee_id', $assesseeId)->where('overall_result', 'not_yet_competent')->count(),
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
