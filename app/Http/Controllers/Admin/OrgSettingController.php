<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrgSetting;
use Illuminate\Http\Request;

class OrgSettingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $group = $request->input('group');

        $settings = OrgSetting::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                      ->orWhere('value', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($group, function ($query, $group) {
                $query->where('group', $group);
            })
            ->paginate(10)
            ->withQueryString();

        $groups = OrgSetting::select('group')->distinct()->pluck('group');

        return view('admin.lsp.settings.index', compact('settings', 'search', 'group', 'groups'));
    }

    public function create()
    {
        return view('admin.lsp.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:org_settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:string,number,boolean,json,date',
            'group' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'is_editable' => 'boolean',
        ]);

        $validated['is_public'] = $request->has('is_public');
        $validated['is_editable'] = $request->has('is_editable');

        OrgSetting::create($validated);

        return redirect()->route('admin.org-settings.index')
            ->with('success', 'Organization setting created successfully.');
    }

    public function edit(OrgSetting $orgSetting)
    {
        return view('admin.lsp.settings.edit', compact('orgSetting'));
    }

    public function update(Request $request, OrgSetting $orgSetting)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:org_settings,key,' . $orgSetting->id,
            'value' => 'nullable|string',
            'type' => 'required|in:string,number,boolean,json,date',
            'group' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'is_editable' => 'boolean',
        ]);

        $validated['is_public'] = $request->has('is_public');
        $validated['is_editable'] = $request->has('is_editable');

        $orgSetting->update($validated);

        return redirect()->route('admin.org-settings.index')
            ->with('success', 'Organization setting updated successfully.');
    }

    public function destroy(OrgSetting $orgSetting)
    {
        if (!$orgSetting->is_editable) {
            return redirect()->route('admin.org-settings.index')
                ->with('error', 'This setting cannot be deleted.');
        }

        $orgSetting->delete();

        return redirect()->route('admin.org-settings.index')
            ->with('success', 'Organization setting deleted successfully.');
    }
}
