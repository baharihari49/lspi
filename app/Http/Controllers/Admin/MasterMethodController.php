<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMethod;
use Illuminate\Http\Request;

class MasterMethodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = MasterMethod::select('category')->distinct()->pluck('category');

        $methods = MasterMethod::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.master-data.methods.index', compact('methods', 'categories', 'search'));
    }

    public function create()
    {
        $categories = MasterMethod::select('category')->distinct()->pluck('category');
        return view('admin.master-data.methods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MasterMethod::create($validated);

        return redirect()
            ->route('admin.master-methods.index')
            ->with('success', 'Method created successfully!');
    }

    public function edit(MasterMethod $masterMethod)
    {
        $categories = MasterMethod::select('category')->distinct()->pluck('category');
        return view('admin.master-data.methods.edit', compact('masterMethod', 'categories'));
    }

    public function update(Request $request, MasterMethod $masterMethod)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $masterMethod->update($validated);

        return redirect()
            ->route('admin.master-methods.index')
            ->with('success', 'Method updated successfully!');
    }

    public function destroy(MasterMethod $masterMethod)
    {
        $masterMethod->delete();

        return redirect()
            ->route('admin.master-methods.index')
            ->with('success', 'Method deleted successfully!');
    }
}
