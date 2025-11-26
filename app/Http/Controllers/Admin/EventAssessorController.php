<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAssessor;
use App\Models\Assessor;
use App\Models\EventSession;
use Illuminate\Http\Request;

class EventAssessorController extends Controller
{
    public function index(Event $event)
    {
        $assessors = $event->assessors()
            ->with(['assessor', 'session'])
            ->orderBy('status')
            ->paginate(15);

        return view('admin.event-assessors.index', compact('event', 'assessors'));
    }

    public function create(Event $event)
    {
        $availableAssessors = Assessor::where('verification_status', 'verified')
            ->orderBy('full_name')
            ->get();
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-assessors.create', compact('event', 'availableAssessors', 'sessions'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'role' => 'required|in:lead,examiner,observer',
            'notes' => 'nullable|string',
            'status' => 'required|in:invited,confirmed,rejected,completed',
            'honorarium_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,paid,cancelled',
        ]);

        $validated['event_id'] = $event->id;
        $validated['invited_at'] = now();

        // Set default payment_status if not provided
        if (empty($validated['payment_status'])) {
            $validated['payment_status'] = 'pending';
        }

        EventAssessor::create($validated);

        return redirect()
            ->route('admin.events.assessors.index', $event)
            ->with('success', 'Assessor assigned successfully');
    }

    public function edit(Event $event, EventAssessor $assessor)
    {
        $assessor->load(['assessor', 'session']);
        $availableAssessors = Assessor::where('verification_status', 'verified')
            ->orderBy('full_name')
            ->get();
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-assessors.edit', compact('event', 'assessor', 'availableAssessors', 'sessions'));
    }

    public function update(Request $request, Event $event, EventAssessor $assessor)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'role' => 'required|in:lead,examiner,observer',
            'notes' => 'nullable|string',
            'status' => 'required|in:invited,confirmed,rejected,completed',
            'honorarium_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,paid,cancelled',
        ]);

        $assessor->update($validated);

        return redirect()
            ->route('admin.events.assessors.index', $event)
            ->with('success', 'Assessor assignment updated successfully');
    }

    public function destroy(Event $event, EventAssessor $assessor)
    {
        $assessor->delete();

        return redirect()
            ->route('admin.events.assessors.index', $event)
            ->with('success', 'Assessor assignment deleted successfully');
    }

    public function confirm(Request $request, Event $event, EventAssessor $assessor)
    {
        $assessor->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Assessor assignment confirmed successfully');
    }

    public function reject(Request $request, Event $event, EventAssessor $assessor)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $assessor->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Assessor assignment rejected');
    }
}
