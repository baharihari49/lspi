<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMethod;
use Illuminate\Http\Request;

class MasterMethodController extends Controller
{
    public function index()
    {
        $methods = MasterMethod::orderBy('category')->orderBy('name')->paginate(20);
        $categories = MasterMethod::select('category')->distinct()->pluck('category');

        return view('admin.master-data.methods.index', compact('methods', 'categories'));
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
