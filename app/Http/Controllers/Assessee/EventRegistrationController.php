<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Apl01Form;
use App\Models\Assessee;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRegistrationController extends Controller
{
    /**
     * Get or create assessee record for current user
     */
    private function getOrCreateAssessee()
    {
        $user = auth()->user();

        if (!$user->assessee) {
            // Create assessee record if not exists
            $assessee = Assessee::create([
                'user_id' => $user->id,
                'registration_number' => 'ASS-' . date('Y') . '-' . str_pad(Assessee::count() + 1, 5, '0', STR_PAD_LEFT),
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'id_number' => '', // To be filled by user later in APL-01 form
                'status' => 'active',
            ]);

            // Refresh user relationship
            $user->refresh();

            return $assessee;
        }

        return $user->assessee;
    }

    /**
     * Display available events for registration.
     */
    public function index(Request $request)
    {
        $query = Event::where('is_published', true)
            ->where('is_active', true)
            ->where('registration_end', '>=', now())
            ->where('registration_start', '<=', now());

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $events = $query->with(['scheme'])
            ->orderBy('start_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Get user's registered events
        $assessee = auth()->user()->assessee;
        $registeredEventIds = [];
        if ($assessee) {
            $registeredEventIds = Apl01Form::where('assessee_id', $assessee->id)
                ->pluck('event_id')
                ->toArray();
        }

        // Get schemes for filter
        $schemes = \App\Models\Scheme::where('is_active', true)->get();

        return view('assessee.events.index', compact('events', 'registeredEventIds', 'schemes'));
    }

    /**
     * Show event details.
     */
    public function show(Event $event)
    {
        if (!$event->is_published || !$event->is_active) {
            abort(404);
        }

        $event->load(['scheme.units']);

        // Check if already registered
        $assessee = auth()->user()->assessee;
        $existingRegistration = null;
        if ($assessee) {
            $existingRegistration = Apl01Form::where('assessee_id', $assessee->id)
                ->where('event_id', $event->id)
                ->first();
        }

        // Check if registration is open
        $isRegistrationOpen = $event->registration_start <= now()
            && $event->registration_end >= now()
            && $event->is_published
            && $event->is_active;

        // Check available slots
        $registeredCount = $event->current_participants ?? Apl01Form::where('event_id', $event->id)->count();
        $availableSlots = $event->max_participants ? $event->max_participants - $registeredCount : null;

        return view('assessee.events.show', compact('event', 'existingRegistration', 'isRegistrationOpen', 'availableSlots', 'registeredCount'));
    }

    /**
     * Register to an event (creates APL-01 form).
     */
    public function register(Request $request, Event $event)
    {
        // Validate registration is open
        if ($event->registration_start > now() || $event->registration_end < now()) {
            return back()->with('error', 'Pendaftaran untuk event ini sudah ditutup.');
        }

        if (!$event->is_published || !$event->is_active) {
            return back()->with('error', 'Event tidak tersedia.');
        }

        // Check max participants
        $registeredCount = $event->current_participants ?? Apl01Form::where('event_id', $event->id)->count();
        if ($event->max_participants && $registeredCount >= $event->max_participants) {
            return back()->with('error', 'Kuota peserta untuk event ini sudah penuh.');
        }

        // Get or create assessee
        $assessee = $this->getOrCreateAssessee();

        // Check if already registered
        $existing = Apl01Form::where('assessee_id', $assessee->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return redirect()->route('admin.my-apl01.show', $existing)
                ->with('info', 'Anda sudah terdaftar di event ini.');
        }

        // Create APL-01 form with data from assessee
        $apl01 = Apl01Form::create([
            'assessee_id' => $assessee->id,
            'event_id' => $event->id,
            'scheme_id' => $event->scheme_id,
            'form_number' => 'APL01-' . date('Y') . '-' . str_pad(Apl01Form::count() + 1, 5, '0', STR_PAD_LEFT),
            'status' => 'draft',
            'certification_purpose' => $request->certification_purpose ?? 'Sertifikasi Kompetensi',
            // Auto-fill from assessee data
            'full_name' => $assessee->full_name,
            'id_number' => $assessee->id_number,
            'date_of_birth' => $assessee->date_of_birth,
            'place_of_birth' => $assessee->place_of_birth,
            'gender' => $assessee->gender,
            'nationality' => $assessee->nationality,
            'email' => $assessee->email,
            'mobile' => $assessee->mobile,
            'phone' => $assessee->phone,
            'address' => $assessee->address,
            'city' => $assessee->city,
            'province' => $assessee->province,
            'postal_code' => $assessee->postal_code,
            'current_company' => $assessee->current_company,
            'current_position' => $assessee->current_position,
            'current_industry' => $assessee->current_industry,
        ]);

        // Update current participants count
        $event->increment('current_participants');

        return redirect()->route('admin.my-apl01.show', $apl01)
            ->with('success', 'Pendaftaran berhasil! Silakan lengkapi formulir APL-01 Anda.');
    }
}
