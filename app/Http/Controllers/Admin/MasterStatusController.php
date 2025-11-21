<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterStatus;
use Illuminate\Http\Request;

class MasterStatusController extends Controller
{
    public function index()
    {
        $statuses = MasterStatus::orderBy('category')->orderBy('sort_order')->paginate(20);
        $categories = MasterStatus::select('category')->distinct()->pluck('category');

        return view('admin.master-data.statuses.index', compact('statuses', 'categories'));
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
