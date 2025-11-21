<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'icon' => 'required|string',
            'icon_color' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // max 5MB
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'lspi/news',
                'transformation' => [
                    'width' => 1200,
                    'height' => 630,
                    'crop' => 'limit',
                    'quality' => 'auto:good',
                ]
            ]);

            $validated['image'] = $uploadedFileUrl->getSecurePath();
            $validated['image_public_id'] = $uploadedFileUrl->getPublicId();
        }

        if (!isset($validated['is_published'])) {
            $validated['is_published'] = false;
        }

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'icon' => 'required|string',
            'icon_color' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // max 5MB
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($news->image_public_id) {
                Cloudinary::destroy($news->image_public_id);
            }

            // Upload new image
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'lspi/news',
                'transformation' => [
                    'width' => 1200,
                    'height' => 630,
                    'crop' => 'limit',
                    'quality' => 'auto:good',
                ]
            ]);

            $validated['image'] = $uploadedFileUrl->getSecurePath();
            $validated['image_public_id'] = $uploadedFileUrl->getPublicId();
        }

        if (!isset($validated['is_published'])) {
            $validated['is_published'] = false;
        }

        if ($validated['is_published'] && empty($validated['published_at']) && !$news->is_published) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Delete image from Cloudinary if exists
        if ($news->image_public_id) {
            Cloudinary::destroy($news->image_public_id);
        }

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus!');
    }
}
