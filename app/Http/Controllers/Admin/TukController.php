<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tuk;
use App\Models\MasterStatus;
use App\Models\User;
use Illuminate\Http\Request;

class TukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tuks = Tuk::with(['status', 'manager'])
            ->withCount(['facilities', 'documents', 'schedules'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%")
                      ->orWhere('province', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.tuk.index', compact('tuks', 'search'));
    }

    public function create()
    {
        $statuses = MasterStatus::where('category', 'general')->get();
        $users = User::where('is_active', true)->get();
        return view('admin.tuk.create', compact('statuses', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:tuk,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:permanent,temporary,mobile',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:0',
            'room_count' => 'required|integer|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'status_id' => 'nullable|exists:master_statuses,id',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        Tuk::create($validated);

        return redirect()->route('admin.tuk.index')
            ->with('success', 'TUK created successfully.');
    }

    public function show(Tuk $tuk)
    {
        $tuk->load(['status', 'manager', 'facilities', 'documents', 'schedules']);
        return view('admin.tuk.show', compact('tuk'));
    }

    public function edit(Tuk $tuk)
    {
        $statuses = MasterStatus::where('category', 'general')->get();
        $users = User::where('is_active', true)->get();
        return view('admin.tuk.edit', compact('tuk', 'statuses', 'users'));
    }

    public function update(Request $request, Tuk $tuk)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:tuk,code,' . $tuk->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:permanent,temporary,mobile',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:0',
            'room_count' => 'required|integer|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'status_id' => 'nullable|exists:master_statuses,id',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->id();

        $tuk->update($validated);

        return redirect()->route('admin.tuk.index')
            ->with('success', 'TUK updated successfully.');
    }

    public function destroy(Tuk $tuk)
    {
        $tuk->delete();

        return redirect()->route('admin.tuk.index')
            ->with('success', 'TUK deleted successfully.');
    }
}
