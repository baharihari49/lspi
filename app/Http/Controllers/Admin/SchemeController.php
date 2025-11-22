<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterStatus;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $sector = $request->get('sector');
        $status = $request->get('status');

        $schemes = Scheme::with(['status', 'creator'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('occupation_title', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($query, $type) {
                $query->where('scheme_type', $type);
            })
            ->when($sector, function ($query, $sector) {
                $query->where('sector', $sector);
            })
            ->when($status, function ($query, $status) {
                $query->where('status_id', $status);
            })
            ->latest()
            ->paginate(15);

        $statuses = MasterStatus::where('category', 'scheme')->get();
        $sectors = Scheme::distinct()->pluck('sector')->filter();

        return view('admin.schemes.index', compact('schemes', 'search', 'type', 'sector', 'status', 'statuses', 'sectors'));
    }

    public function create()
    {
        $statuses = MasterStatus::where('category', 'scheme')->get();
        return view('admin.schemes.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:schemes,code',
            'name' => 'required|string|max:255',
            'occupation_title' => 'nullable|string|max:255',
            'qualification_level' => 'nullable|in:I,II,III,IV,V,VI,VII,VIII,IX',
            'description' => 'nullable|string',
            'scheme_type' => 'required|in:occupation,cluster,qualification',
            'sector' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
            'effective_date' => 'nullable|date',
            'review_date' => 'nullable|date|after:effective_date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $scheme = Scheme::create($validated);

        return redirect()
            ->route('admin.schemes.show', $scheme)
            ->with('success', 'Scheme created successfully.');
    }

    public function show(Scheme $scheme)
    {
        $scheme->load(['versions.units.elements.criteria', 'versions.requirements', 'versions.documents', 'status', 'creator']);

        return view('admin.schemes.show', compact('scheme'));
    }

    public function edit(Scheme $scheme)
    {
        $statuses = MasterStatus::where('category', 'scheme')->get();
        return view('admin.schemes.edit', compact('scheme', 'statuses'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:schemes,code,' . $scheme->id,
            'name' => 'required|string|max:255',
            'occupation_title' => 'nullable|string|max:255',
            'qualification_level' => 'nullable|in:I,II,III,IV,V,VI,VII,VIII,IX',
            'description' => 'nullable|string',
            'scheme_type' => 'required|in:occupation,cluster,qualification',
            'sector' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
            'effective_date' => 'nullable|date',
            'review_date' => 'nullable|date|after:effective_date',
        ]);

        $validated['updated_by'] = auth()->id();

        $scheme->update($validated);

        return redirect()
            ->route('admin.schemes.show', $scheme)
            ->with('success', 'Scheme updated successfully.');
    }

    public function destroy(Scheme $scheme)
    {
        try {
            $scheme->delete();

            return redirect()
                ->route('admin.schemes.index')
                ->with('success', 'Scheme deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.schemes.index')
                ->with('error', 'Failed to delete scheme. It may be in use.');
        }
    }
}
