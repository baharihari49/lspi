<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\SchemeVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchemeVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scheme $scheme)
    {
        $scheme->load(['versions' => function ($query) {
            $query->withCount(['units', 'requirements', 'documents'])
                  ->with('approver')
                  ->latest();
        }]);

        return view('admin.scheme-versions.index', compact('scheme'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Scheme $scheme)
    {
        return view('admin.scheme-versions.create', compact('scheme'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Scheme $scheme)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'changes_summary' => 'nullable|string',
            'status' => 'required|in:draft,active,archived,deprecated',
            'is_current' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        DB::transaction(function () use ($scheme, $validated) {
            // If this version should be current, unset other current versions
            if (!empty($validated['is_current'])) {
                $scheme->versions()->update(['is_current' => false]);
            }

            $version = $scheme->versions()->create([
                'version' => $validated['version'],
                'changes_summary' => $validated['changes_summary'] ?? null,
                'status' => $validated['status'],
                'is_current' => !empty($validated['is_current']),
                'effective_date' => $validated['effective_date'] ?? null,
                'expiry_date' => $validated['expiry_date'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // If status is active and is current, auto-approve
            if ($version->status === 'active' && $version->is_current) {
                $version->update([
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('admin.schemes.versions.index', $scheme)
            ->with('success', 'Version created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme, SchemeVersion $version)
    {
        $version->load([
            'units.elements.criteria',
            'requirements',
            'documents.documentType',
            'approver',
            'creator',
            'updater'
        ]);

        return view('admin.scheme-versions.show', compact('scheme', 'version'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme, SchemeVersion $version)
    {
        return view('admin.scheme-versions.edit', compact('scheme', 'version'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme, SchemeVersion $version)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'changes_summary' => 'nullable|string',
            'status' => 'required|in:draft,active,archived,deprecated',
            'is_current' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        DB::transaction(function () use ($scheme, $version, $validated) {
            // If this version should be current, unset other current versions
            if (!empty($validated['is_current']) && !$version->is_current) {
                $scheme->versions()->where('id', '!=', $version->id)->update(['is_current' => false]);
            }

            $version->update([
                'version' => $validated['version'],
                'changes_summary' => $validated['changes_summary'] ?? null,
                'status' => $validated['status'],
                'is_current' => !empty($validated['is_current']),
                'effective_date' => $validated['effective_date'] ?? null,
                'expiry_date' => $validated['expiry_date'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            // If status changed to active and is current, auto-approve if not already approved
            if ($version->status === 'active' && $version->is_current && !$version->approved_at) {
                $version->update([
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('admin.schemes.versions.show', [$scheme, $version])
            ->with('success', 'Version updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme, SchemeVersion $version)
    {
        // Prevent deleting current version
        if ($version->is_current) {
            return back()->with('error', 'Cannot delete the current version');
        }

        $version->delete();

        return redirect()
            ->route('admin.schemes.versions.index', $scheme)
            ->with('success', 'Version deleted successfully');
    }

    /**
     * Set version as current
     */
    public function setCurrent(Scheme $scheme, SchemeVersion $version)
    {
        DB::transaction(function () use ($scheme, $version) {
            // Unset all current versions
            $scheme->versions()->update(['is_current' => false]);

            // Set this version as current
            $version->update([
                'is_current' => true,
                'updated_by' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Version set as current successfully');
    }

    /**
     * Approve version
     */
    public function approve(Scheme $scheme, SchemeVersion $version)
    {
        $version->update([
            'status' => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Version approved successfully');
    }
}
