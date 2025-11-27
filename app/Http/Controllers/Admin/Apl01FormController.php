<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl01Answer;
use App\Models\Apl01Form;
use App\Models\Apl01FormField;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\Event;
use App\Services\CertificationFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Apl01FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Apl01Form::with(['assessee', 'scheme', 'event', 'currentReviewer'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter by assessee
        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Search by form number or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('form_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $forms = $query->paginate(20);
        $schemes = Scheme::active()->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.apl01.index', compact('forms', 'schemes', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $assessees = Assessee::active()->get();
        $schemes = Scheme::active()->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        // If assessee is pre-selected
        $selectedAssessee = null;
        if ($request->filled('assessee_id')) {
            $selectedAssessee = Assessee::find($request->assessee_id);
        }

        // If scheme is pre-selected
        $selectedScheme = null;
        if ($request->filled('scheme_id')) {
            $selectedScheme = Scheme::find($request->scheme_id);
        }

        return view('admin.apl01.create', compact('assessees', 'schemes', 'events', 'selectedAssessee', 'selectedScheme'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'event_id' => 'nullable|exists:events,id',
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'current_industry' => 'nullable|string|max:255',
            'certification_purpose' => 'nullable|string',
            'target_competency' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $form = new Apl01Form($validated);

            // Generate form number
            $form->form_number = $form->generateFormNumber();
            $form->status = 'draft';
            $form->submission_date = now();
            $form->created_by = auth()->id();
            $form->save();

            // Save dynamic field answers
            $this->saveFieldAnswers($form, $request);

            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $form)
                ->with('success', 'APL-01 Form created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create form: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Apl01Form $apl01)
    {
        $apl01->load([
            'assessee',
            'scheme.formFields' => function ($query) {
                $query->active()
                    ->visible()
                    ->orderBy('section')
                    ->orderBy('order');
            },
            'event',
            'answers.formField',
            'reviews.reviewer',
            'currentReviewer',
            'apl02Units',
        ]);

        return view('admin.apl01.show', compact('apl01'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apl01Form $apl01)
    {
        // Only allow editing if form is in draft or rejected status
        if (!$apl01->is_editable) {
            return back()->with('error', 'This form cannot be edited in its current status.');
        }

        $apl01->load([
            'assessee',
            'scheme.formFields' => function ($query) {
                $query->active()
                    ->visible()
                    ->orderBy('section')
                    ->orderBy('order');
            },
            'answers.formField',
        ]);

        $assessees = Assessee::active()->get();
        $schemes = Scheme::active()->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.apl01.edit', compact('apl01', 'assessees', 'schemes', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apl01Form $apl01)
    {
        // Only allow updating if form is in draft or rejected status
        if (!$apl01->is_editable) {
            return back()->with('error', 'This form cannot be updated in its current status.');
        }

        $validated = $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'event_id' => 'nullable|exists:events,id',
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'current_industry' => 'nullable|string|max:255',
            'certification_purpose' => 'nullable|string',
            'target_competency' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $apl01->fill($validated);
            $apl01->updated_by = auth()->id();
            $apl01->save();

            // Save dynamic field answers
            $this->saveFieldAnswers($apl01, $request);

            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $apl01)
                ->with('success', 'APL-01 Form updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update form: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apl01Form $apl01)
    {
        // Only allow deletion if form is in draft status
        if ($apl01->status !== 'draft') {
            return back()->with('error', 'Only draft forms can be deleted.');
        }

        DB::beginTransaction();
        try {
            $apl01->delete();
            DB::commit();

            return redirect()
                ->route('admin.apl01.index')
                ->with('success', 'APL-01 Form deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete form: ' . $e->getMessage());
        }
    }

    /**
     * Auto-fill form data from assessee
     */
    public function autofill(Request $request)
    {
        $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
        ]);

        $assessee = Assessee::find($request->assessee_id);

        return response()->json([
            'success' => true,
            'data' => [
                'full_name' => $assessee->full_name,
                'id_number' => $assessee->id_number,
                'date_of_birth' => $assessee->date_of_birth?->format('Y-m-d'),
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
            ],
        ]);
    }

    /**
     * Submit form for review
     */
    public function submit(Apl01Form $apl01)
    {
        if (!$apl01->canBeSubmitted()) {
            return back()->with('error', 'Form cannot be submitted. Please ensure all required fields are completed and declaration is agreed.');
        }

        DB::beginTransaction();
        try {
            $apl01->submit(auth()->id());
            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $apl01)
                ->with('success', 'Form submitted successfully and is now under review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit form: ' . $e->getMessage());
        }
    }

    /**
     * Accept submitted form for review
     */
    public function acceptForReview(Apl01Form $apl01)
    {
        if ($apl01->status !== 'submitted') {
            return back()->with('error', 'Form harus dalam status submitted untuk diterima review.');
        }

        DB::beginTransaction();
        try {
            // Don't pass auth()->id() - let the model auto-assign from event assessors
            $apl01->acceptForReview();
            DB::commit();

            return back()->with('success', 'Form berhasil diterima untuk review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menerima form: ' . $e->getMessage());
        }
    }

    /**
     * Generate APL-02 units manually
     */
    public function generateApl02(Apl01Form $apl01, CertificationFlowService $flowService)
    {
        if ($apl01->status !== 'approved') {
            return back()->with('error', 'APL-01 harus disetujui terlebih dahulu sebelum membuat APL-02.');
        }

        if ($apl01->apl02_generated_at) {
            return back()->with('warning', 'APL-02 sudah dibuat sebelumnya pada ' . $apl01->apl02_generated_at->format('d M Y H:i'));
        }

        $result = $flowService->triggerApl02Generation($apl01);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Update declaration status
     */
    public function updateDeclaration(Request $request, Apl01Form $apl01)
    {
        $request->validate([
            'declaration_agreed' => 'required|boolean',
        ]);

        if (!$apl01->is_editable) {
            return back()->with('error', 'Declaration cannot be updated for this form.');
        }

        DB::beginTransaction();
        try {
            $apl01->declaration_agreed = $request->declaration_agreed;
            if ($request->declaration_agreed) {
                $apl01->declaration_signed_at = now();
            } else {
                $apl01->declaration_signed_at = null;
            }
            $apl01->save();

            DB::commit();

            return back()->with('success', 'Declaration updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update declaration: ' . $e->getMessage());
        }
    }

    /**
     * Get dynamic form fields for a scheme (AJAX)
     */
    public function getSchemeFields(Request $request)
    {
        $request->validate([
            'scheme_id' => 'required|exists:schemes,id',
            'apl01_form_id' => 'nullable|exists:apl01_forms,id',
        ]);

        $fields = Apl01FormField::where('scheme_id', $request->scheme_id)
            ->active()
            ->visible()
            ->orderBy('section')
            ->orderBy('order')
            ->get();

        // If editing, get existing answers
        $answers = [];
        if ($request->filled('apl01_form_id')) {
            $answers = Apl01Answer::where('apl01_form_id', $request->apl01_form_id)
                ->pluck('answer_value', 'form_field_id')
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'fields' => $fields->map(function ($field) use ($answers) {
                return [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_label' => $field->field_label,
                    'field_description' => $field->field_description,
                    'field_type' => $field->field_type,
                    'field_options' => $field->field_options,
                    'is_required' => $field->is_required,
                    'placeholder' => $field->placeholder,
                    'help_text' => $field->help_text,
                    'section' => $field->section,
                    'default_value' => $field->default_value,
                    'answer' => $answers[$field->id] ?? null,
                ];
            }),
        ]);
    }

    /**
     * Save dynamic field answers
     */
    protected function saveFieldAnswers(Apl01Form $form, Request $request): void
    {
        $fields = Apl01FormField::where('scheme_id', $form->scheme_id)
            ->active()
            ->visible()
            ->get();

        foreach ($fields as $field) {
            $fieldKey = 'dynamic_field_' . $field->id;
            $value = $request->input($fieldKey);

            // Handle file uploads
            if ($field->field_type === 'file' && $request->hasFile($fieldKey)) {
                $file = $request->file($fieldKey);
                $path = $file->store('apl01-answers/' . $form->id, 'public');
                $value = $path;
            }

            // Skip if no value and not required
            if ($value === null && !$field->is_required) {
                continue;
            }

            // Handle array values (checkboxes, multi-select)
            $answerJson = null;
            if (is_array($value)) {
                $answerJson = $value;
                $value = implode(', ', $value);
            }

            // Update or create answer
            Apl01Answer::updateOrCreate(
                [
                    'apl01_form_id' => $form->id,
                    'form_field_id' => $field->id,
                ],
                [
                    'answer_value' => $value,
                    'answer_json' => $answerJson,
                    'is_valid' => true,
                    'review_status' => 'pending',
                ]
            );
        }
    }
}
