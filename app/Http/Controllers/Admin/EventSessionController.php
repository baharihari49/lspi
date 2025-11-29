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
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
            'is_active' => 'boolean',
        ]);

        // Generate session code: EVT-001-S001
        // Include soft deleted sessions to avoid duplicate codes
        $lastSession = EventSession::withTrashed()
            ->where('event_id', $event->id)
            ->where('session_code', 'like', $event->code . '-S%')
            ->orderBy('session_code', 'desc')
            ->first();

        if ($lastSession) {
            $lastNumber = (int) substr($lastSession->session_code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $validated['session_code'] = $event->code . '-S' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $validated['event_id'] = $event->id;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['status'] = $validated['status'] ?? 'scheduled';
        $validated['current_participants'] = 0;
        $validated['order'] = $newNumber;

        EventSession::create($validated);

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session berhasil dibuat');
    }

    public function edit(Event $event, EventSession $session)
    {
        return view('admin.event-sessions.edit', compact('event', 'session'));
    }

    public function update(Request $request, Event $event, EventSession $session)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $session->update($validated);

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session berhasil diperbarui');
    }

    public function destroy(Event $event, EventSession $session)
    {
        $session->delete();

        return redirect()
            ->route('admin.events.sessions.index', $event)
            ->with('success', 'Session deleted successfully');
    }
}
