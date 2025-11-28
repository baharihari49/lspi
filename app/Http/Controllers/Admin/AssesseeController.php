<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessee;
use App\Models\MasterStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssesseeController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessee::with(['user', 'status']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $assessees = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.assessees.index', compact('assessees'));
    }

    public function create()
    {
        // Only get users with 'assessee' role who don't have an assessee profile yet
        $users = User::where('is_active', true)
            ->whereHas('roles', function ($query) {
                $query->where('slug', 'assessee');
            })
            ->whereDoesntHave('assessee')
            ->orderBy('name')
            ->get();
        $statuses = MasterStatus::where('category', 'assessee')->orderBy('sort_order')->get();

        return view('admin.assessees.create', compact('users', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:assessees,user_id',
            'registration_number' => 'nullable|unique:assessees,registration_number',
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:assessees,id_number',
            'id_type' => 'required|in:ktp,passport,kitas',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'current_industry' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'verification_status' => 'nullable|in:pending,verified,rejected,suspended',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
        ]);

        // Set default verification_status if not provided
        if (empty($validated['verification_status'])) {
            $validated['verification_status'] = 'pending';
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-photos', $fileName, 'public');
            $validated['photo'] = $filePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        // Auto-generate registration number if not provided
        if (empty($validated['registration_number'])) {
            $validated['registration_number'] = 'ASE-' . date('Y') . '-' . str_pad(Assessee::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        Assessee::create($validated);

        return redirect()
            ->route('admin.assessees.index')
            ->with('success', 'Assessee created successfully');
    }

    public function show(Assessee $assessee)
    {
        $assessee->load([
            'user',
            'status',
            'documents.documentType',
            'employmentInfo',
            'educationHistory',
            'experiences',
        ]);

        return view('admin.assessees.show', compact('assessee'));
    }

    public function edit(Assessee $assessee)
    {
        // Only get users with 'assessee' role who don't have an assessee profile yet, or the current user
        $users = User::where('is_active', true)
            ->whereHas('roles', function ($query) {
                $query->where('slug', 'assessee');
            })
            ->where(function ($q) use ($assessee) {
                $q->whereDoesntHave('assessee')
                    ->orWhere('id', $assessee->user_id);
            })
            ->orderBy('name')
            ->get();
        $statuses = MasterStatus::where('category', 'assessee')->orderBy('sort_order')->get();

        return view('admin.assessees.edit', compact('assessee', 'users', 'statuses'));
    }

    public function update(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:assessees,user_id,' . $assessee->id,
            'registration_number' => 'nullable|unique:assessees,registration_number,' . $assessee->id,
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:assessees,id_number,' . $assessee->id,
            'id_type' => 'required|in:ktp,passport,kitas',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'current_industry' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'verification_status' => 'required|in:pending,verified,rejected,suspended',
            'verification_notes' => 'nullable|string',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($assessee->photo) {
                Storage::disk('public')->delete($assessee->photo);
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-photos', $fileName, 'public');
            $validated['photo'] = $filePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = Auth::id();

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $assessee->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }

        $assessee->update($validated);

        return redirect()
            ->route('admin.assessees.show', $assessee)
            ->with('success', 'Assessee updated successfully');
    }

    public function destroy(Assessee $assessee)
    {
        // Delete photo if exists
        if ($assessee->photo) {
            Storage::disk('public')->delete($assessee->photo);
        }

        $assessee->delete();

        return redirect()
            ->route('admin.assessees.index')
            ->with('success', 'Assessee deleted successfully');
    }

    public function verify(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $assessee->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Assessee verification updated successfully');
    }
}
