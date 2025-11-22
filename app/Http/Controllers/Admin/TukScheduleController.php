<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TukSchedule;
use App\Models\Tuk;
use Illuminate\Http\Request;

class TukScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tukId = $request->input('tuk_id');
        $status = $request->input('status');

        $schedules = TukSchedule::with(['tuk', 'creator'])
            ->when($search, function ($query, $search) {
                $query->whereHas('tuk', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($tukId, function ($query, $tukId) {
                $query->where('tuk_id', $tukId);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        $tuks = Tuk::active()->get();

        return view('admin.tuk.schedules.index', compact('schedules', 'search', 'tukId', 'status', 'tuks'));
    }

    public function create()
    {
        $tuks = Tuk::active()->get();
        return view('admin.tuk.schedules.create', compact('tuks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,booked,blocked,maintenance',
            'available_capacity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        TukSchedule::create($validated);

        return redirect()->route('admin.tuk-schedules.index')
            ->with('success', 'TUK schedule created successfully.');
    }

    public function edit(TukSchedule $tukSchedule)
    {
        $tuks = Tuk::active()->get();
        return view('admin.tuk.schedules.edit', compact('tukSchedule', 'tuks'));
    }

    public function update(Request $request, TukSchedule $tukSchedule)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,booked,blocked,maintenance',
            'available_capacity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $tukSchedule->update($validated);

        return redirect()->route('admin.tuk-schedules.index')
            ->with('success', 'TUK schedule updated successfully.');
    }

    public function destroy(TukSchedule $tukSchedule)
    {
        $tukSchedule->delete();

        return redirect()->route('admin.tuk-schedules.index')
            ->with('success', 'TUK schedule deleted successfully.');
    }
}
