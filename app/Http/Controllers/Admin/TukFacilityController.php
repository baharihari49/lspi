<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TukFacility;
use App\Models\Tuk;
use Illuminate\Http\Request;

class TukFacilityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tukId = $request->input('tuk_id');

        $facilities = TukFacility::with('tuk')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($tukId, function ($query, $tukId) {
                $query->where('tuk_id', $tukId);
            })
            ->paginate(10)
            ->withQueryString();

        $tuks = Tuk::active()->get();

        return view('admin.tuk.facilities.index', compact('facilities', 'search', 'tukId', 'tuks'));
    }

    public function create()
    {
        $tuks = Tuk::active()->get();
        return view('admin.tuk.facilities.create', compact('tuks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'name' => 'required|string|max:255',
            'category' => 'required|in:equipment,furniture,technology,safety,other',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after:last_maintenance',
            'notes' => 'nullable|string',
        ]);

        TukFacility::create($validated);

        return redirect()->route('admin.tuk-facilities.index')
            ->with('success', 'TUK facility created successfully.');
    }

    public function edit(TukFacility $tukFacility)
    {
        $tuks = Tuk::active()->get();
        return view('admin.tuk.facilities.edit', compact('tukFacility', 'tuks'));
    }

    public function update(Request $request, TukFacility $tukFacility)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'name' => 'required|string|max:255',
            'category' => 'required|in:equipment,furniture,technology,safety,other',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after:last_maintenance',
            'notes' => 'nullable|string',
        ]);

        $tukFacility->update($validated);

        return redirect()->route('admin.tuk-facilities.index')
            ->with('success', 'TUK facility updated successfully.');
    }

    public function destroy(TukFacility $tukFacility)
    {
        $tukFacility->delete();

        return redirect()->route('admin.tuk-facilities.index')
            ->with('success', 'TUK facility deleted successfully.');
    }
}
