<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTuk;
use App\Models\Tuk;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventTukController extends Controller
{
    public function index(Event $event)
    {
        $tuks = $event->tuks()
            ->with(['tuk', 'session'])
            ->orderBy('is_primary', 'desc')
            ->paginate(15);

        return view('admin.event-tuk.index', compact('event', 'tuks'));
    }

    public function create(Event $event)
    {
        $availableTuks = Tuk::orderBy('name')->get();
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-tuk.create', compact('event', 'availableTuks', 'sessions'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuks,id',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $validated['event_id'] = $event->id;
        $validated['is_primary'] = $request->has('is_primary');

        EventTuk::create($validated);

        return redirect()
            ->route('admin.events.tuk.index', $event)
            ->with('success', 'TUK assigned successfully');
    }

    public function edit(Event $event, EventTuk $tuk)
    {
        $tuk->load(['tuk', 'session']);
        $availableTuks = Tuk::orderBy('name')->get();
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-tuk.edit', compact('event', 'tuk', 'availableTuks', 'sessions'));
    }

    public function update(Request $request, Event $event, EventTuk $tuk)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuks,id',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        $tuk->update($validated);

        return redirect()
            ->route('admin.events.tuk.index', $event)
            ->with('success', 'TUK assignment updated successfully');
    }

    public function destroy(Event $event, EventTuk $tuk)
    {
        $tuk->delete();

        return redirect()
            ->route('admin.events.tuk.index', $event)
            ->with('success', 'TUK assignment deleted successfully');
    }

    public function confirm(Request $request, Event $event, EventTuk $tuk)
    {
        $tuk->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'TUK assignment confirmed successfully');
    }
}
