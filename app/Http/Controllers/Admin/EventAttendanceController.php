<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventAttendanceController extends Controller
{
    public function index(Event $event, Request $request)
    {
        $query = $event->attendance()
            ->with(['user', 'session', 'checkedInBy', 'checkedOutBy']);

        // Filter by session
        if ($request->filled('session_id')) {
            $query->where('event_session_id', $request->session_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendance = $query->orderBy('check_in_at', 'desc')->paginate(15);
        $sessions = $event->sessions()->orderBy('session_date')->get();

        return view('admin.event-attendance.index', compact('event', 'attendance', 'sessions'));
    }

    public function create(Event $event)
    {
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.event-attendance.create', compact('event', 'sessions', 'users'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_session_id' => 'required|exists:event_sessions,id',
            'check_in_at' => 'required|date',
            'check_in_method' => 'required|in:manual,qr_code,biometric,online',
            'check_in_location' => 'nullable|string|max:255',
            'status' => 'required|in:present,absent,excused,late',
            'notes' => 'nullable|string',
        ]);

        $validated['event_id'] = $event->id;
        $validated['checked_in_by'] = Auth::id();

        EventAttendance::create($validated);

        return redirect()
            ->route('admin.events.attendance.index', $event)
            ->with('success', 'Attendance recorded successfully');
    }

    public function edit(Event $event, EventAttendance $attendance)
    {
        $attendance->load(['user', 'session']);
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.event-attendance.edit', compact('event', 'attendance', 'sessions', 'users'));
    }

    public function update(Request $request, Event $event, EventAttendance $attendance)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_session_id' => 'required|exists:event_sessions,id',
            'check_in_at' => 'required|date',
            'check_out_at' => 'nullable|date|after:check_in_at',
            'check_in_method' => 'required|in:manual,qr_code,biometric,online',
            'check_out_method' => 'nullable|in:manual,qr_code,biometric,online',
            'check_in_location' => 'nullable|string|max:255',
            'check_out_location' => 'nullable|string|max:255',
            'status' => 'required|in:present,absent,excused,late',
            'notes' => 'nullable|string',
            'excuse_reason' => 'nullable|string',
        ]);

        if ($request->filled('check_out_at') && !$attendance->check_out_at) {
            $validated['checked_out_by'] = Auth::id();
        }

        $attendance->update($validated);

        return redirect()
            ->route('admin.events.attendance.index', $event)
            ->with('success', 'Attendance updated successfully');
    }

    public function destroy(Event $event, EventAttendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('admin.events.attendance.index', $event)
            ->with('success', 'Attendance deleted successfully');
    }

    public function checkIn(Request $request, Event $event, EventAttendance $attendance)
    {
        if ($attendance->check_in_at) {
            return redirect()
                ->back()
                ->with('error', 'Participant already checked in');
        }

        $attendance->update([
            'check_in_at' => now(),
            'check_in_method' => $request->input('check_in_method', 'manual'),
            'check_in_location' => $request->input('check_in_location'),
            'status' => 'present',
            'checked_in_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Check-in successful');
    }

    public function checkOut(Request $request, Event $event, EventAttendance $attendance)
    {
        if (!$attendance->check_in_at) {
            return redirect()
                ->back()
                ->with('error', 'Participant must check in first');
        }

        if ($attendance->check_out_at) {
            return redirect()
                ->back()
                ->with('error', 'Participant already checked out');
        }

        $attendance->update([
            'check_out_at' => now(),
            'check_out_method' => $request->input('check_out_method', 'manual'),
            'check_out_location' => $request->input('check_out_location'),
            'checked_out_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Check-out successful');
    }
}
