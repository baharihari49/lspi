<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeVersion;
use App\Models\SchemeUnit;
use App\Models\SchemeElement;
use Illuminate\Http\Request;

class SchemeElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        $unit->load(['elements' => function ($query) {
            $query->withCount(['criteria'])->orderBy('order');
        }]);

        return view('admin.scheme-elements.index', compact('scheme', 'version', 'unit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        return view('admin.scheme-elements.create', compact('scheme', 'version', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Scheme $scheme, SchemeVersion $version, SchemeUnit $unit)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $unit->elements()->create([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit])
            ->with('success', 'Element created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        $element->load('criteria');
        return view('admin.scheme-elements.show', compact('scheme', 'version', 'unit', 'element'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        return view('admin.scheme-elements.edit', compact('scheme', 'version', 'unit', 'element'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $element->update([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit])
            ->with('success', 'Element updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme, SchemeVersion $version, SchemeUnit $unit, SchemeElement $element)
    {
        $element->delete();

        return redirect()
            ->route('admin.schemes.versions.units.elements.index', [$scheme, $version, $unit])
            ->with('success', 'Element deleted successfully');
    }
}
