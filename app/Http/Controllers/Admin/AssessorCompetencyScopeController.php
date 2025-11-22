<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessor;
use App\Models\AssessorCompetencyScope;
use Illuminate\Http\Request;

class AssessorCompetencyScopeController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessorCompetencyScope::with(['assessor', 'approver']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('scheme_code', 'like', "%{$search}%")
                    ->orWhere('scheme_name', 'like', "%{$search}%")
                    ->orWhere('competency_unit_code', 'like', "%{$search}%")
                    ->orWhere('certificate_number', 'like', "%{$search}%")
                    ->orWhereHas('assessor', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by assessor
        if ($request->filled('assessor_id')) {
            $query->where('assessor_id', $request->assessor_id);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by expiring soon
        if ($request->filled('expiring_soon')) {
            $query->expiringSoon($request->expiring_soon);
        }

        // Filter by expired
        if ($request->boolean('expired')) {
            $query->expired();
        }

        $competencyScopes = $query->latest()->paginate(15);
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.competency-scopes.index', compact('competencyScopes', 'assessors'));
    }

    public function create()
    {
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.competency-scopes.create', compact('assessors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'scheme_code' => 'required|string|max:50',
            'scheme_name' => 'required|string|max:255',
            'competency_unit_code' => 'required|string|max:50',
            'competency_unit_title' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:100',
            'certificate_issued_date' => 'nullable|date',
            'certificate_expiry_date' => 'nullable|date|after:certificate_issued_date',
            'is_active' => 'boolean',
        ]);

        $validated['approval_status'] = 'pending';

        AssessorCompetencyScope::create($validated);

        return redirect()->route('admin.assessor-competency-scopes.index')
            ->with('success', 'Competency scope created successfully.');
    }

    public function show(AssessorCompetencyScope $assessorCompetencyScope)
    {
        $assessorCompetencyScope->load(['assessor', 'approver']);

        return view('admin.assessors.competency-scopes.show', compact('assessorCompetencyScope'));
    }

    public function edit(AssessorCompetencyScope $assessorCompetencyScope)
    {
        $assessors = Assessor::where('is_active', true)
            ->orWhere('id', $assessorCompetencyScope->assessor_id)
            ->get();

        return view('admin.assessors.competency-scopes.edit', compact('assessorCompetencyScope', 'assessors'));
    }

    public function update(Request $request, AssessorCompetencyScope $assessorCompetencyScope)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'scheme_code' => 'required|string|max:50',
            'scheme_name' => 'required|string|max:255',
            'competency_unit_code' => 'required|string|max:50',
            'competency_unit_title' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:100',
            'certificate_issued_date' => 'nullable|date',
            'certificate_expiry_date' => 'nullable|date|after:certificate_issued_date',
            'is_active' => 'boolean',
        ]);

        $assessorCompetencyScope->update($validated);

        return redirect()->route('admin.assessor-competency-scopes.index')
            ->with('success', 'Competency scope updated successfully.');
    }

    public function destroy(AssessorCompetencyScope $assessorCompetencyScope)
    {
        $assessorCompetencyScope->delete();

        return redirect()->route('admin.assessor-competency-scopes.index')
            ->with('success', 'Competency scope deleted successfully.');
    }

    // Approval action
    public function approve(Request $request, AssessorCompetencyScope $assessorCompetencyScope)
    {
        $validated = $request->validate([
            'approval_status' => 'required|in:approved,rejected,expired',
            'approval_notes' => 'nullable|string',
        ]);

        $assessorCompetencyScope->update([
            'approval_status' => $validated['approval_status'],
            'approval_notes' => $validated['approval_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Competency scope approval status updated.');
    }
}
