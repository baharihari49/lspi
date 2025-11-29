<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Scheme;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');

        $events = Event::with(['scheme', 'status', 'creator'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($query, $type) {
                $query->where('event_type', $type);
            })
            ->when($status, function ($query, $status) {
                $query->where('status_id', $status);
            })
            ->latest()
            ->paginate(15);

        $statuses = MasterStatus::where('category', 'event')->get();

        return view('admin.events.index', compact('events', 'search', 'type', 'status', 'statuses'));
    }

    public function create()
    {
        $schemes = Scheme::active()->get();
        $statuses = MasterStatus::where('category', 'event')->get();

        // Generate next event code for preview
        $nextCode = $this->generateEventCode();

        return view('admin.events.create', compact('schemes', 'statuses', 'nextCode'));
    }

    /**
     * Generate unique event code.
     * Format: EVT-YYYYMM-XXXX (e.g., EVT-202511-0001)
     */
    protected function generateEventCode(): string
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "EVT-{$year}{$month}-";

        $lastEvent = Event::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        if ($lastEvent) {
            $lastNumber = (int) substr($lastEvent->code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scheme_id' => 'nullable|exists:schemes,id',
            'description' => 'nullable|string',
            'event_type' => 'required|in:certification,training,workshop,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'status_id' => 'required|exists:master_statuses,id',
            'is_active' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
            'location_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Auto-generate event code
        $validated['code'] = $this->generateEventCode();

        $event = Event::create([
            ...$validated,
            'current_participants' => 0,
            'is_active' => !empty($validated['is_active']),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event berhasil dibuat');
    }

    public function show(Event $event)
    {
        $event->load([
            'scheme',
            'status',
            'sessions' => function ($query) {
                $query->orderBy('session_date')->orderBy('start_time');
            },
            'tuks.tuk',
            'assessors.assessor',
            'materials',
            'attendance.user',
            'creator',
            'updater',
        ]);

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $schemes = Scheme::active()->get();
        $statuses = MasterStatus::where('category', 'event')->get();

        return view('admin.events.edit', compact('event', 'schemes', 'statuses'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:events,code,' . $event->id,
            'name' => 'required|string|max:255',
            'scheme_id' => 'nullable|exists:schemes,id',
            'description' => 'nullable|string',
            'event_type' => 'required|in:certification,training,workshop,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'status_id' => 'required|exists:master_statuses,id',
            'is_active' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
            'location_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $event->update([
            ...$validated,
            'is_active' => !empty($validated['is_active']),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
