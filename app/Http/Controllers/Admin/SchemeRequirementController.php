<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeVersion;
use App\Models\SchemeRequirement;
use Illuminate\Http\Request;

class SchemeRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scheme $scheme, SchemeVersion $version)
    {
        $version->load(['requirements' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('admin.scheme-requirements.index', compact('scheme', 'version'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Scheme $scheme, SchemeVersion $version)
    {
        return view('admin.scheme-requirements.create', compact('scheme', 'version'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Scheme $scheme, SchemeVersion $version)
    {
        $validated = $request->validate([
            'requirement_type' => 'required|in:education,experience,certification,training,physical,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_mandatory' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $version->requirements()->create([
            'requirement_type' => $validated['requirement_type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_mandatory' => !empty($validated['is_mandatory']),
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.requirements.index', [$scheme, $version])
            ->with('success', 'Requirement created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme, SchemeVersion $version, SchemeRequirement $requirement)
    {
        return view('admin.scheme-requirements.show', compact('scheme', 'version', 'requirement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme, SchemeVersion $version, SchemeRequirement $requirement)
    {
        return view('admin.scheme-requirements.edit', compact('scheme', 'version', 'requirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme, SchemeVersion $version, SchemeRequirement $requirement)
    {
        $validated = $request->validate([
            'requirement_type' => 'required|in:education,experience,certification,training,physical,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_mandatory' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $requirement->update([
            'requirement_type' => $validated['requirement_type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_mandatory' => !empty($validated['is_mandatory']),
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.requirements.index', [$scheme, $version])
            ->with('success', 'Requirement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme, SchemeVersion $version, SchemeRequirement $requirement)
    {
        $requirement->delete();

        return redirect()
            ->route('admin.schemes.versions.requirements.index', [$scheme, $version])
            ->with('success', 'Requirement deleted successfully');
    }
}
