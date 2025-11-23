<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apl02EvidenceMap extends Model
{
    use SoftDeletes;

    protected $table = 'apl02_evidence_map';

    protected $fillable = [
        'apl02_evidence_id',
        'scheme_element_id',
        'coverage_level',
        'coverage_percentage',
        'mapping_notes',
        'assessor_evaluation',
        'evaluated_by',
        'evaluated_at',
        'evaluation_notes',
        'relevance_score',
        'quality_score',
        'currency_score',
        'authenticity_score',
        'is_primary',
        'display_order',
        'metadata',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'coverage_percentage' => 'decimal:2',
        'relevance_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'currency_score' => 'decimal:2',
        'authenticity_score' => 'decimal:2',
        'metadata' => 'array',
        'is_primary' => 'boolean',
        'display_order' => 'integer',
    ];

    // Relationships
    public function evidence(): BelongsTo
    {
        return $this->belongsTo(Apl02Evidence::class, 'apl02_evidence_id');
    }

    public function schemeElement(): BelongsTo
    {
        return $this->belongsTo(SchemeElement::class);
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    // Scopes
    public function scopeByEvidence($query, $evidenceId)
    {
        return $query->where('apl02_evidence_id', $evidenceId);
    }

    public function scopeByElement($query, $elementId)
    {
        return $query->where('scheme_element_id', $elementId);
    }

    public function scopeByCoverageLevel($query, $level)
    {
        return $query->where('coverage_level', $level);
    }

    public function scopeFullCoverage($query)
    {
        return $query->where('coverage_level', 'full');
    }

    public function scopePartialCoverage($query)
    {
        return $query->where('coverage_level', 'partial');
    }

    public function scopeAccepted($query)
    {
        return $query->where('assessor_evaluation', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('assessor_evaluation', 'rejected');
    }

    public function scopePending($query)
    {
        return $query->where('assessor_evaluation', 'pending');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Accessors
    public function getCoverageLevelLabelAttribute(): string
    {
        return match($this->coverage_level) {
            'full' => 'Full Coverage',
            'partial' => 'Partial Coverage',
            'supplementary' => 'Supplementary',
            default => ucfirst($this->coverage_level),
        };
    }

    public function getAssessorEvaluationLabelAttribute(): string
    {
        return match($this->assessor_evaluation) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'requires_more_evidence' => 'Requires More Evidence',
            default => ucfirst($this->assessor_evaluation),
        };
    }

    public function getAverageScoreAttribute(): ?float
    {
        $scores = array_filter([
            $this->relevance_score,
            $this->quality_score,
            $this->currency_score,
            $this->authenticity_score,
        ]);

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    public function getIsAcceptedAttribute(): bool
    {
        return $this->assessor_evaluation === 'accepted';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->assessor_evaluation === 'rejected';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->assessor_evaluation === 'pending';
    }

    // Helper Methods
    public function evaluate($evaluation, $evaluatorId, $notes = null, $scores = []): bool
    {
        $this->assessor_evaluation = $evaluation;
        $this->evaluated_by = $evaluatorId;
        $this->evaluated_at = now();
        $this->evaluation_notes = $notes;

        if (isset($scores['relevance'])) {
            $this->relevance_score = $scores['relevance'];
        }
        if (isset($scores['quality'])) {
            $this->quality_score = $scores['quality'];
        }
        if (isset($scores['currency'])) {
            $this->currency_score = $scores['currency'];
        }
        if (isset($scores['authenticity'])) {
            $this->authenticity_score = $scores['authenticity'];
        }

        return $this->save();
    }

    public function accept($evaluatorId, $notes = null, $scores = []): bool
    {
        return $this->evaluate('accepted', $evaluatorId, $notes, $scores);
    }

    public function reject($evaluatorId, $notes): bool
    {
        return $this->evaluate('rejected', $evaluatorId, $notes);
    }

    public function setPrimary(): bool
    {
        // Remove primary flag from other mappings for this element
        static::where('scheme_element_id', $this->scheme_element_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        $this->is_primary = true;
        return $this->save();
    }

    public function calculateCoveragePercentage(): void
    {
        switch ($this->coverage_level) {
            case 'full':
                $this->coverage_percentage = 100;
                break;
            case 'partial':
                $this->coverage_percentage = $this->coverage_percentage ?? 50;
                break;
            case 'supplementary':
                $this->coverage_percentage = $this->coverage_percentage ?? 25;
                break;
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($map) {
            if ($map->coverage_percentage === null) {
                $map->calculateCoveragePercentage();
            }
        });
    }
}
