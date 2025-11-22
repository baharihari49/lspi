<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeVersion;
use App\Models\SchemeUnit;
use App\Models\SchemeElement;
use App\Models\SchemeCriterion;
use Illuminate\Http\Request;

class SchemeCriterionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        $element->load(['criteria' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('admin.scheme-criteria.index', compact('scheme', 'version', 'unit', 'element'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        return view('admin.scheme-criteria.create', compact('scheme', 'version', 'unit', 'element'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'description' => 'required|string',
            'evidence_guide' => 'nullable|string',
            'assessment_method' => 'nullable|in:written,practical,oral,portfolio,observation',
            'order' => 'nullable|integer|min:0',
        ]);

        $element->criteria()->create([
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'],
            'evidence_guide' => $validated['evidence_guide'] ?? null,
            'assessment_method' => $validated['assessment_method'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element])
            ->with('success', 'Criterion created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element, SchemeCriterion $criterion)
    {
        return view('admin.scheme-criteria.show', compact('scheme', 'version', 'unit', 'element', 'criterion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element, SchemeCriterion $criterion)
    {
        return view('admin.scheme-criteria.edit', compact('scheme', 'version', 'unit', 'element', 'criterion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element, SchemeCriterion $criterion)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'description' => 'required|string',
            'evidence_guide' => 'nullable|string',
            'assessment_method' => 'nullable|in:written,practical,oral,portfolio,observation',
            'order' => 'nullable|integer|min:0',
        ]);

        $criterion->update([
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'],
            'evidence_guide' => $validated['evidence_guide'] ?? null,
            'assessment_method' => $validated['assessment_method'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element])
            ->with('success', 'Criterion updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element, SchemeCriterion $criterion)
    {
        $criterion->delete();

        return redirect()
            ->route('admin.schemes.versions.units.elements.criteria.index', [$scheme, $version, $unit, $element])
            ->with('success', 'Criterion deleted successfully');
    }
}
