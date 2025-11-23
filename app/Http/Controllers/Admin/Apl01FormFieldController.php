<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl01FormField;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Apl01FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Apl01FormField::with(['scheme', 'createdBy', 'updatedBy']);

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter by field type
        if ($request->filled('field_type')) {
            $query->where('field_type', $request->field_type);
        }

        // Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search by field name or label
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('field_name', 'like', "%{$search}%")
                    ->orWhere('field_label', 'like', "%{$search}%");
            });
        }

        $fields = $query->orderBy('scheme_id')
            ->orderBy('section')
            ->orderBy('order')
            ->paginate(30);

        $schemes = Scheme::active()->get();

        return view('admin.apl01.fields.index', compact('fields', 'schemes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schemes = Scheme::active()->get();

        // If scheme is pre-selected
        $selectedScheme = null;
        if ($request->filled('scheme_id')) {
            $selectedScheme = Scheme::find($request->scheme_id);
        }

        return view('admin.apl01.fields.create', compact('schemes', 'selectedScheme'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scheme_id' => 'required|exists:schemes,id',
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_description' => 'nullable|string',
            'field_type' => 'required|in:text,textarea,number,email,date,select,radio,checkbox,checkboxes,file,image,url,phone,rating,yesno,section_header,html',
            'field_options' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'is_required' => 'boolean',
            'is_enabled' => 'boolean',
            'is_visible' => 'boolean',
            'conditional_logic' => 'nullable|array',
            'default_value' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'file_config' => 'nullable|array',
            'css_class' => 'nullable|string|max:255',
            'wrapper_class' => 'nullable|string|max:255',
            'field_width' => 'nullable|integer|min:1|max:12',
            'section' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'help_text' => 'nullable|string',
            'tooltip' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check for unique field_name per scheme
        $exists = Apl01FormField::where('scheme_id', $request->scheme_id)
            ->where('field_name', $request->field_name)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Field name already exists for this scheme.');
        }

        DB::beginTransaction();
        try {
            $field = new Apl01FormField($validated);
            $field->created_by = auth()->id();
            $field->save();

            DB::commit();

            return redirect()
                ->route('admin.apl01.fields.index', ['scheme_id' => $field->scheme_id])
                ->with('success', 'Form field created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create field: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Apl01FormField $field)
    {
        $field->load(['scheme', 'createdBy', 'updatedBy']);

        return view('admin.apl01.fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apl01FormField $field)
    {
        $field->load('scheme');
        $schemes = Scheme::active()->get();

        return view('admin.apl01.fields.edit', compact('field', 'schemes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apl01FormField $field)
    {
        $validated = $request->validate([
            'scheme_id' => 'required|exists:schemes,id',
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_description' => 'nullable|string',
            'field_type' => 'required|in:text,textarea,number,email,date,select,radio,checkbox,checkboxes,file,image,url,phone,rating,yesno,section_header,html',
            'field_options' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'is_required' => 'boolean',
            'is_enabled' => 'boolean',
            'is_visible' => 'boolean',
            'conditional_logic' => 'nullable|array',
            'default_value' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'file_config' => 'nullable|array',
            'css_class' => 'nullable|string|max:255',
            'wrapper_class' => 'nullable|string|max:255',
            'field_width' => 'nullable|integer|min:1|max:12',
            'section' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'help_text' => 'nullable|string',
            'tooltip' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check for unique field_name per scheme (excluding current field)
        $exists = Apl01FormField::where('scheme_id', $request->scheme_id)
            ->where('field_name', $request->field_name)
            ->where('id', '!=', $field->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Field name already exists for this scheme.');
        }

        DB::beginTransaction();
        try {
            $field->fill($validated);
            $field->updated_by = auth()->id();
            $field->save();

            DB::commit();

            return redirect()
                ->route('admin.apl01.fields.show', $field)
                ->with('success', 'Form field updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update field: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apl01FormField $field)
    {
        // Check if field has answers
        if ($field->answers()->count() > 0) {
            return back()->with('error', 'Cannot delete field that has answers.');
        }

        DB::beginTransaction();
        try {
            $field->delete();
            DB::commit();

            return redirect()
                ->route('admin.apl01.fields.index')
                ->with('success', 'Form field deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete field: ' . $e->getMessage());
        }
    }

    /**
     * Reorder fields
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:apl01_form_fields,id',
            'fields.*.order' => 'required|integer|min:0',
            'fields.*.section' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->fields as $fieldData) {
                Apl01FormField::where('id', $fieldData['id'])->update([
                    'order' => $fieldData['order'],
                    'section' => $fieldData['section'] ?? null,
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Fields reordered successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder fields: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Duplicate field
     */
    public function duplicate(Apl01FormField $field)
    {
        DB::beginTransaction();
        try {
            $newField = $field->replicate();
            $newField->field_name = $field->field_name . '_copy';
            $newField->field_label = $field->field_label . ' (Copy)';
            $newField->order = $field->order + 1;
            $newField->created_by = auth()->id();
            $newField->updated_by = null;
            $newField->save();

            DB::commit();

            return redirect()
                ->route('admin.apl01.fields.edit', $newField)
                ->with('success', 'Field duplicated successfully. Please update the field name and label.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to duplicate field: ' . $e->getMessage());
        }
    }

    /**
     * Get fields by scheme (AJAX)
     */
    public function getByScheme(Request $request, Scheme $scheme)
    {
        $fields = $scheme->formFields()
            ->active()
            ->visible()
            ->orderBy('section')
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'fields' => $fields,
        ]);
    }
}
