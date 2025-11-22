<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMaterial;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventMaterialController extends Controller
{
    public function index(Event $event)
    {
        $materials = $event->materials()
            ->with(['session', 'uploader'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.event-materials.index', compact('event', 'materials'));
    }

    public function create(Event $event)
    {
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-materials.create', compact('event', 'sessions'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_type' => 'required|in:presentation,handout,video,document,other',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'file' => 'required|file|max:51200', // 50MB max
            'is_public' => 'boolean',
            'is_downloadable' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('event-materials', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $validated['event_id'] = $event->id;
        $validated['is_public'] = $request->has('is_public');
        $validated['is_downloadable'] = $request->has('is_downloadable');
        $validated['uploaded_by'] = Auth::id();
        $validated['published_at'] = now();

        EventMaterial::create($validated);

        return redirect()
            ->route('admin.events.materials.index', $event)
            ->with('success', 'Material uploaded successfully');
    }

    public function edit(Event $event, EventMaterial $material)
    {
        $material->load(['session', 'uploader']);
        $sessions = $event->sessions()->orderBy('session_date')->orderBy('start_time')->get();

        return view('admin.event-materials.edit', compact('event', 'material', 'sessions'));
    }

    public function update(Request $request, Event $event, EventMaterial $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_type' => 'required|in:presentation,handout,video,document,other',
            'event_session_id' => 'nullable|exists:event_sessions,id',
            'file' => 'nullable|file|max:51200', // 50MB max
            'is_public' => 'boolean',
            'is_downloadable' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('event-materials', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $validated['is_public'] = $request->has('is_public');
        $validated['is_downloadable'] = $request->has('is_downloadable');

        $material->update($validated);

        return redirect()
            ->route('admin.events.materials.index', $event)
            ->with('success', 'Material updated successfully');
    }

    public function destroy(Event $event, EventMaterial $material)
    {
        // Delete file from storage
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('admin.events.materials.index', $event)
            ->with('success', 'Material deleted successfully');
    }

    public function download(Event $event, EventMaterial $material)
    {
        if (!$material->is_downloadable) {
            abort(403, 'This material is not downloadable');
        }

        // Increment download count
        $material->increment('download_count');

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }
}
