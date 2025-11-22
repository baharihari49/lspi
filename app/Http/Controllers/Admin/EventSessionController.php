<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventSessionController extends Controller
{
    public function index(Event $event)
    {
        $sessions = $event->sessions()
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->paginate(15);

        return view('admin.event-sessions.index', compact('event', 'sessions'));
    }

    public function create(Event $event)
    {
        return view('admin.event-sessions.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session_type' => 'required|in:theory,practice,exam,other',
            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_mandatory' => 'boolean',
        ]);

        $validated['event_id'] = $event->id;
        $validated['is_mandatory'] = $request->has('is_mandatory');
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        EventSession::create($validated);

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session created successfully');
    }

    public function edit(Event $event, EventSession $session)
    {
        return view('admin.event-sessions.edit', compact('event', 'session'));
    }

    public function update(Request $request, Event $event, EventSession $session)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session_type' => 'required|in:theory,practice,exam,other',
            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_mandatory' => 'boolean',
        ]);

        $validated['is_mandatory'] = $request->has('is_mandatory');
        $validated['updated_by'] = Auth::id();

        $session->update($validated);

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session updated successfully');
    }

    public function destroy(Event $event, EventSession $session)
    {
        $session->delete();

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session deleted successfully');
    }
}
