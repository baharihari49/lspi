<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchemeElement;
use App\Models\SchemeUnit;
use App\Models\Scheme;
use Illuminate\Http\Request;

class KukController extends Controller
{
    /**
     * Display a listing of all KUK (Kriteria Unjuk Kerja / Scheme Elements).
     */
    public function index(Request $request)
    {
        $query = SchemeElement::with(['schemeUnit.schemeVersion.scheme', 'criteria']);

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->whereHas('schemeUnit.schemeVersion', function ($q) use ($request) {
                $q->where('scheme_id', $request->scheme_id);
            });
        }

        // Filter by unit
        if ($request->filled('unit_id')) {
            $query->where('scheme_unit_id', $request->unit_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $elements = $query->orderBy('code')->paginate(20)->withQueryString();

        // Get schemes for filter
        $schemes = Scheme::orderBy('name')->get();

        // Get units for filter (if scheme selected)
        $units = collect([]);
        if ($request->filled('scheme_id')) {
            $units = SchemeUnit::whereHas('schemeVersion', function ($q) use ($request) {
                $q->where('scheme_id', $request->scheme_id)->where('is_current', true);
            })->orderBy('code')->get();
        }

        return view('admin.kuk.index', compact('elements', 'schemes', 'units'));
    }

    /**
     * Display the specified KUK.
     */
    public function show(SchemeElement $kuk)
    {
        $kuk->load(['schemeUnit.schemeVersion.scheme', 'criteria']);

        return view('admin.kuk.show', compact('kuk'));
    }
}
