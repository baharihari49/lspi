<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeVersion;
use App\Models\SchemeUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemeUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scheme $scheme, SchemeVersion $version)
    {
        $version->load(['units' => function ($query) {
            $query->withCount(['elements'])->orderBy('order');
        }]);

        return view('admin.scheme-units.index', compact('scheme', 'version'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Scheme $scheme, SchemeVersion $version)
    {
        return view('admin.scheme-units.create', compact('scheme', 'version'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Scheme $scheme, SchemeVersion $version)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_type' => 'required|in:core,optional,supporting',
            'credit_hours' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
        ]);

        $version->units()->create([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'unit_type' => $validated['unit_type'],
            'credit_hours' => $validated['credit_hours'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_mandatory' => !empty($validated['is_mandatory']),
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.index', [$scheme, $version])
            ->with('success', 'Unit created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        $unit->load(['elements.criteria']);
        return view('admin.scheme-units.show', compact('scheme', 'version', 'unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        return view('admin.scheme-units.edit', compact('scheme', 'version', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_type' => 'required|in:core,optional,supporting',
            'credit_hours' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
        ]);

        $unit->update([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'unit_type' => $validated['unit_type'],
            'credit_hours' => $validated['credit_hours'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_mandatory' => !empty($validated['is_mandatory']),
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.index', [$scheme, $version])
            ->with('success', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        $unit->delete();

        return redirect()
            ->route('admin.schemes.versions.units.index', [$scheme, $version])
            ->with('success', 'Unit deleted successfully');
    }
}
