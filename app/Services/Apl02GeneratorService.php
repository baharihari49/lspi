<?php

namespace App\Services;

use App\Models\Apl01Form;
use App\Models\Apl02Form;
use App\Models\Apl02Unit;
use App\Models\SchemeUnit;
use App\Models\SchemeVersion;
use Illuminate\Support\Collection;

class Apl02GeneratorService
{
    /**
     * Generate APL-02 units from an approved APL-01 form.
     */
    public function generateFromApl01(Apl01Form $apl01): array
    {
        // Check if already generated
        if ($apl01->apl02_generated_at) {
            throw new \Exception('APL-02 units already generated for this APL-01 form.');
        }

        $schemeUnits = $this->getSchemeUnits($apl01->scheme_id);

        if ($schemeUnits->isEmpty()) {
            throw new \Exception('No scheme units found for the selected scheme.');
        }

        $generatedUnits = [];

        foreach ($schemeUnits as $schemeUnit) {
            $generatedUnits[] = $this->createApl02Unit($apl01, $schemeUnit);
        }

        return $generatedUnits;
    }

    /**
     * Get scheme units for a given scheme.
     */
    protected function getSchemeUnits(int $schemeId): Collection
    {
        // Try to get units from current scheme version
        $currentVersion = SchemeVersion::where('scheme_id', $schemeId)
            ->where('is_current', true)
            ->first();

        if ($currentVersion) {
            $units = SchemeUnit::where('scheme_version_id', $currentVersion->id)
                ->orderBy('order')
                ->get();

            if ($units->isNotEmpty()) {
                return $units;
            }
        }

        // Fallback: get all units from all versions of this scheme
        $versionIds = SchemeVersion::where('scheme_id', $schemeId)->pluck('id');
        return SchemeUnit::whereIn('scheme_version_id', $versionIds)
            ->orderBy('order')
            ->get();
    }

    /**
     * Create a single APL-02 unit.
     */
    protected function createApl02Unit(Apl01Form $apl01, SchemeUnit $schemeUnit): Apl02Unit
    {
        return Apl02Unit::create([
            'assessee_id' => $apl01->assessee_id,
            'scheme_id' => $apl01->scheme_id,
            'scheme_unit_id' => $schemeUnit->id,
            'event_id' => $apl01->event_id,
            'apl01_form_id' => $apl01->id,
            'unit_code' => $schemeUnit->code,
            'unit_title' => $schemeUnit->title ?? $schemeUnit->name,
            'unit_description' => $schemeUnit->description,
            'status' => 'not_started',
            'total_evidence' => 0,
            'completion_percentage' => 0,
            'auto_generated' => true,
        ]);
    }

    /**
     * Check if APL-02 units exist for an APL-01 form.
     */
    public function hasExistingUnits(Apl01Form $apl01): bool
    {
        return Apl02Unit::where('apl01_form_id', $apl01->id)->exists();
    }

    /**
     * Get the count of units that would be generated.
     */
    public function getUnitCount(int $schemeId): int
    {
        return $this->getSchemeUnits($schemeId)->count();
    }

    // ================================================================
    // APL-02 Form (per-Scheme) Methods - NEW FLOW
    // ================================================================

    /**
     * Generate APL-02 Form (per-scheme) from an approved APL-01 form.
     */
    public function generateApl02Form(Apl01Form $apl01): Apl02Form
    {
        // Check if already generated
        if ($apl01->apl02Form) {
            throw new \Exception('APL-02 Form already exists for this APL-01.');
        }

        $schemeUnits = $this->getSchemeUnits($apl01->scheme_id);

        if ($schemeUnits->isEmpty()) {
            throw new \Exception('No scheme units found for the selected scheme.');
        }

        // Create the APL-02 Form
        $apl02Form = new Apl02Form();
        $apl02Form->form_number = $apl02Form->generateFormNumber();
        $apl02Form->apl01_form_id = $apl01->id;
        $apl02Form->assessee_id = $apl01->assessee_id;
        $apl02Form->scheme_id = $apl01->scheme_id;
        $apl02Form->event_id = $apl01->event_id;
        $apl02Form->status = 'draft';
        $apl02Form->auto_generated = true;
        $apl02Form->save();

        // Initialize self-assessment with scheme units
        $apl02Form->initializeSelfAssessment();

        // Update APL-01 flow status
        $apl01->updateFlowStatus('apl02_generated');

        return $apl02Form;
    }

    /**
     * Check if APL-02 Form exists for an APL-01 form.
     */
    public function hasExistingForm(Apl01Form $apl01): bool
    {
        return Apl02Form::where('apl01_form_id', $apl01->id)->exists();
    }

    /**
     * Get existing APL-02 Form for an APL-01.
     */
    public function getExistingForm(Apl01Form $apl01): ?Apl02Form
    {
        return Apl02Form::where('apl01_form_id', $apl01->id)->first();
    }
}
