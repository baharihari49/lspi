<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessorController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessor::with(['user', 'status', 'verifier']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('met_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by registration status
        if ($request->filled('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by expiring soon
        if ($request->filled('expiring_soon')) {
            $query->expiringSoon($request->expiring_soon);
        }

        // Filter by expired
        if ($request->boolean('expired')) {
            $query->expired();
        }

        $assessors = $query->latest()->paginate(15);

        return view('admin.assessors.index', compact('assessors'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('assessor')->get();

        return view('admin.assessors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:assessors,user_id',
            'full_name' => 'required|string|max:255',
            'id_card_number' => 'required|string|size:16|unique:assessors,id_card_number',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'education_level' => 'required|string|max:100',
            'major' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'registration_date' => 'required|date',
            'valid_until' => 'required|date|after:registration_date',
            'registration_status' => 'required|in:active,inactive,suspended,expired',
            'is_active' => 'boolean',
        ]);

        // Generate registration number
        $validated['registration_number'] = $this->generateRegistrationNumber();
        $validated['verification_status'] = 'pending';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('photos/assessors', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['photo_file_id'] = $fileRecord->id;
        }

        Assessor::create($validated);

        return redirect()->route('admin.assessors.index')
            ->with('success', 'Assessor created successfully.');
    }

    public function show(Assessor $assessor)
    {
        $assessor->load([
            'user',
            'photo',
            'status',
            'verifier',
            'documents.documentType',
            'competencyScopes',
            'experiences',
            'bankInfo.bankStatementFile',
            'bankInfo.npwpFile',
        ]);

        return view('admin.assessors.show', compact('assessor'));
    }

    public function edit(Assessor $assessor)
    {
        $users = User::whereDoesntHave('assessor')
            ->orWhere('id', $assessor->user_id)
            ->get();

        return view('admin.assessors.edit', compact('assessor', 'users'));
    }

    public function update(Request $request, Assessor $assessor)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:assessors,user_id,' . $assessor->id,
            'full_name' => 'required|string|max:255',
            'id_card_number' => 'required|string|size:16|unique:assessors,id_card_number,' . $assessor->id,
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'education_level' => 'required|string|max:100',
            'major' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'registration_date' => 'required|date',
            'valid_until' => 'required|date|after:registration_date',
            'registration_status' => 'required|in:active,inactive,suspended,expired',
            'is_active' => 'boolean',
            'met_number' => 'nullable|string|max:50|unique:assessors,met_number,' . $assessor->id,
            'verification_status' => 'required|in:pending,verified,rejected',
        ]);

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $assessor->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = auth()->id();
        } elseif ($validated['verification_status'] !== 'verified') {
            $validated['verified_at'] = null;
            $validated['verified_by'] = null;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('photos/assessors', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['photo_file_id'] = $fileRecord->id;
        }

        $assessor->update($validated);

        return redirect()->route('admin.assessors.index')
            ->with('success', 'Assessor updated successfully.');
    }

    public function destroy(Assessor $assessor)
    {
        $assessor->delete();

        return redirect()->route('admin.assessors.index')
            ->with('success', 'Assessor deleted successfully.');
    }

    // Verification action
    public function verify(Request $request, Assessor $assessor)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $assessor->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Assessor verification status updated.');
    }

    private function generateRegistrationNumber()
    {
        $year = date('Y');
        $lastAssessor = Assessor::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastAssessor ? intval(substr($lastAssessor->registration_number, -4)) + 1 : 1;

        return 'ASR-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
