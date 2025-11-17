<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_news' => News::count(),
            'published_news' => News::published()->count(),
            'draft_news' => News::where('is_published', false)->count(),
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::published()->count(),
            'draft_announcements' => Announcement::where('is_published', false)->count(),
        ];

        $recent_news = News::latest()->take(5)->get();
        $recent_announcements = Announcement::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_news', 'recent_announcements'));
    }
}
