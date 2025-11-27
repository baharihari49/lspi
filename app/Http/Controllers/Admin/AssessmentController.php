<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\Event;
use App\Models\Tuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Assessment::with(['assessee', 'scheme', 'leadAssessor', 'event'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by assessee
        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('assessment_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('assessee', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $assessments = $query->paginate(15);

        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get active/published events with their relations
        $events = Event::with(['scheme', 'assessors.assessor', 'apl01Forms.assessee', 'tuks.tuk'])
            ->where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $tuks = Tuk::active()->orderBy('name')->get();

        return view('admin.assessments.create', compact('events', 'tuks'));
    }

    /**
     * Get event data for AJAX request (scheme, assessors, assessees)
     */
    public function getEventData(Event $event)
    {
        $event->load(['scheme', 'assessors.assessor', 'apl01Forms.assessee', 'tuks.tuk']);

        // Get lead assessor (confirmed with lead role)
        $leadAssessor = $event->assessors()
            ->where('status', 'confirmed')
            ->where('role', 'lead')
            ->with('assessor')
            ->first();

        // Get all confirmed assessors
        $assessors = $event->assessors()
            ->where('status', 'confirmed')
            ->with('assessor')
            ->get()
            ->map(function ($ea) {
                return [
                    'id' => $ea->assessor?->user_id,
                    'name' => $ea->assessor?->full_name ?? $ea->assessor?->name,
                    'role' => $ea->role,
                ];
            })
            ->filter(fn($a) => $a['id'] !== null);

        // Get assessees from approved APL-01 forms
        $assessees = $event->apl01Forms()
            ->whereIn('status', ['approved', 'under_review', 'submitted'])
            ->with('assessee')
            ->get()
            ->map(function ($form) {
                return [
                    'id' => $form->assessee_id,
                    'full_name' => $form->assessee?->full_name,
                    'assessee_number' => $form->assessee?->assessee_number,
                    'apl01_form_id' => $form->id,
                ];
            })
            ->filter(fn($a) => $a['id'] !== null);

        // Get TUKs assigned to event
        $tuks = $event->tuks()->with('tuk')->get()->map(function ($et) {
            return [
                'id' => $et->tuk_id,
                'name' => $et->tuk?->name,
            ];
        })->filter(fn($t) => $t['id'] !== null);

        return response()->json([
            'success' => true,
            'data' => [
                'scheme' => $event->scheme ? [
                    'id' => $event->scheme->id,
                    'name' => $event->scheme->name,
                    'code' => $event->scheme->code,
                ] : null,
                'lead_assessor' => $leadAssessor ? [
                    'id' => $leadAssessor->assessor?->user_id,
                    'name' => $leadAssessor->assessor?->full_name ?? $leadAssessor->assessor?->name,
                ] : null,
                'assessors' => $assessors->values(),
                'assessees' => $assessees->values(),
                'tuks' => $tuks->values(),
                'start_date' => $event->start_date?->format('Y-m-d'),
                'location' => $event->location,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'lead_assessor_id' => 'required|exists:users,id',
            'assessment_method' => 'required|in:portfolio,observation,interview,demonstration,written_test,mixed',
            'assessment_type' => 'required|in:initial,verification,surveillance,re_assessment',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string',
            'tuk_id' => 'nullable|exists:tuk,id',
            'planned_duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
        ]);

        // Generate assessment number
        $year = date('Y');
        $lastAssessment = Assessment::where('assessment_number', 'like', "ASM-{$year}-%")
            ->orderBy('assessment_number', 'desc')
            ->first();

        if ($lastAssessment) {
            $lastNumber = intval(substr($lastAssessment->assessment_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['assessment_number'] = "ASM-{$year}-{$newNumber}";
        $validated['status'] = 'draft';
        $validated['overall_result'] = 'pending';
        $validated['created_by'] = auth()->id();

        $assessment = Assessment::create($validated);

        return redirect()->route('admin.assessments.show', $assessment)
            ->with('success', 'Assessment berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load([
            'assessee',
            'scheme',
            'leadAssessor',
            'event.tuks.tuk',
            'tuk',
            'assessmentUnits.criteria',
            'assessmentUnits.assessor',
            'documents',
            'verifications',
            'feedback',
            'results'
        ]);

        return view('admin.assessments.show', compact('assessment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment)
    {
        // Only allow editing if status is draft or scheduled
        if (!$assessment->canBeModified()) {
            return redirect()->route('admin.assessments.show', $assessment)
                ->with('error', 'Assessment tidak dapat diubah karena sudah dalam proses');
        }

        $assessees = Assessee::orderBy('full_name')->get();
        $schemes = Scheme::orderBy('name')->get();
        $events = Event::orderBy('name')->get();
        $tuks = Tuk::active()->orderBy('name')->get();
        $assessors = User::withRole('assessor')->orderBy('name')->get();

        return view('admin.assessments.edit', compact('assessment', 'assessees', 'schemes', 'events', 'tuks', 'assessors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        // Only allow editing if status is draft or scheduled
        if (!$assessment->canBeModified()) {
            return redirect()->route('admin.assessments.show', $assessment)
                ->with('error', 'Assessment tidak dapat diubah karena sudah dalam proses');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'event_id' => 'nullable|exists:events,id',
            'lead_assessor_id' => 'required|exists:users,id',
            'assessment_method' => 'required|in:portfolio,observation,interview,demonstration,written_test,mixed',
            'assessment_type' => 'required|in:initial,verification,surveillance,re_assessment',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string',
            'tuk_id' => 'nullable|exists:tuk,id',
            'planned_duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $assessment->update($validated);

        return redirect()->route('admin.assessments.show', $assessment)
            ->with('success', 'Assessment berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assessment $assessment)
    {
        // Only allow deletion if status is draft
        if ($assessment->status !== 'draft') {
            return redirect()->route('admin.assessments.index')
                ->with('error', 'Hanya assessment dengan status draft yang dapat dihapus');
        }

        $assessment->delete();

        return redirect()->route('admin.assessments.index')
            ->with('success', 'Assessment berhasil dihapus');
    }

    /**
     * Update assessment status
     */
    public function updateStatus(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,scheduled,in_progress,completed,under_review,verified,approved,rejected,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Set timestamps based on status
        if ($validated['status'] === 'in_progress' && !$assessment->started_at) {
            $assessment->started_at = now();
        } elseif ($validated['status'] === 'completed' && !$assessment->completed_at) {
            $assessment->completed_at = now();
            // Calculate duration
            if ($assessment->started_at) {
                $assessment->duration_minutes = $assessment->started_at->diffInMinutes(now());
            }
        }

        $assessment->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $assessment->notes,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Status assessment berhasil diupdate');
    }

    /**
     * Generate assessment units from scheme
     */
    public function generateUnits(Assessment $assessment)
    {
        // Get scheme version
        $schemeVersion = DB::table('scheme_versions')
            ->where('scheme_id', $assessment->scheme_id)
            ->where('is_current', true)
            ->first();

        if (!$schemeVersion) {
            return redirect()->back()
                ->with('error', 'Scheme version tidak ditemukan');
        }

        // Get units from scheme
        $units = DB::table('scheme_units')
            ->where('scheme_version_id', $schemeVersion->id)
            ->orderBy('order')
            ->get();

        if ($units->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada unit kompetensi dalam scheme ini');
        }

        DB::beginTransaction();
        try {
            foreach ($units as $index => $unit) {
                // Create assessment unit
                $assessmentUnit = $assessment->assessmentUnits()->create([
                    'scheme_unit_id' => $unit->id,
                    'assessor_id' => $assessment->lead_assessor_id,
                    'unit_code' => $unit->code,
                    'unit_title' => $unit->title,
                    'unit_description' => $unit->description,
                    'assessment_method' => $assessment->assessment_method,
                    'status' => 'pending',
                    'result' => 'pending',
                    'display_order' => $index,
                ]);

                // Get elements for this unit
                $elements = DB::table('scheme_elements')
                    ->where('scheme_unit_id', $unit->id)
                    ->orderBy('order')
                    ->get();

                // Create assessment criteria for each element
                foreach ($elements as $elemIndex => $element) {
                    $assessmentUnit->criteria()->create([
                        'scheme_element_id' => $element->id,
                        'element_code' => $element->code,
                        'element_title' => $element->title,
                        'assessment_method' => $assessment->assessment_method,
                        'result' => 'pending',
                        'is_critical' => false,
                        'display_order' => $elemIndex,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.assessments.show', $assessment)
                ->with('success', 'Unit kompetensi berhasil di-generate dari scheme');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal generate unit kompetensi: ' . $e->getMessage());
        }
    }
}
