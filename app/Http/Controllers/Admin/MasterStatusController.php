<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterStatus;
use Illuminate\Http\Request;

class MasterStatusController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = MasterStatus::select('category')->distinct()->pluck('category');

        $statuses = MasterStatus::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('label', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.master-data.statuses.index', compact('statuses', 'categories', 'search'));
    }

    public function create()
    {
        $categories = MasterStatus::select('category')->distinct()->pluck('category');
        return view('admin.master-data.statuses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        MasterStatus::create($validated);

        return redirect()
            ->route('admin.master-statuses.index')
            ->with('success', 'Status created successfully!');
    }

    public function edit(MasterStatus $masterStatus)
    {
        $categories = MasterStatus::select('category')->distinct()->pluck('category');
        return view('admin.master-data.statuses.edit', compact('masterStatus', 'categories'));
    }

    public function update(Request $request, MasterStatus $masterStatus)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $masterStatus->update($validated);

        return redirect()
            ->route('admin.master-statuses.index')
            ->with('success', 'Status updated successfully!');
    }

    public function destroy(MasterStatus $masterStatus)
    {
        $masterStatus->delete();

        return redirect()
            ->route('admin.master-statuses.index')
            ->with('success', 'Status deleted successfully!');
    }
}
