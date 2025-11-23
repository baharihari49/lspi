<?php

namespace Database\Seeders;

use App\Models\Apl02Unit;
use App\Models\Apl02Evidence;
use App\Models\Apl02EvidenceMap;
use App\Models\Apl02AssessorReview;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\SchemeUnit;
use App\Models\SchemeElement;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Apl02Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get necessary data
        $assessees = Assessee::all();
        $schemes = Scheme::with('currentVersion.units.elements')->get();
        $events = Event::where('is_active', true)->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->get();

        if ($assessees->isEmpty() || $schemes->isEmpty()) {
            $this->command->warn('Skipping Apl02Seeder: No assessees or schemes found. Please run AssesseeSeeder and SchemeSeeder first.');
            return;
        }

        $this->command->info('Seeding APL-02 Portfolio data...');

        // Create portfolio units for each assessee
        foreach ($assessees->take(10) as $assessee) {
            $scheme = $schemes->random();
            $schemeVersion = $scheme->currentVersion->first();

            if (!$schemeVersion) {
                continue;
            }

            $schemeUnits = $schemeVersion->units;

            if ($schemeUnits->isEmpty()) {
                continue;
            }

            // Create 2-4 units per assessee
            $unitsToCreate = rand(2, min(4, $schemeUnits->count()));

            foreach ($schemeUnits->random($unitsToCreate) as $schemeUnit) {
                // Create unit
                $unit = Apl02Unit::create([
                    'assessee_id' => $assessee->id,
                    'scheme_id' => $scheme->id,
                    'scheme_unit_id' => $schemeUnit->id,
                    'event_id' => $events->isNotEmpty() && rand(0, 1) ? $events->random()->id : null,
                    'assessor_id' => $assessors->isNotEmpty() && rand(0, 1) ? $assessors->random()->id : null,
                    'unit_code' => $schemeUnit->code,
                    'unit_title' => $schemeUnit->title,
                    'status' => $this->randomStatus(),
                    'assessment_result' => $this->randomAssessmentResult(),
                    'assigned_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                    'started_at' => rand(0, 1) ? now()->subDays(rand(1, 20)) : null,
                    'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                    'assessment_notes' => rand(0, 1) ? 'Sample assessment notes for unit ' . $schemeUnit->code : null,
                ]);

                // Get elements for this unit
                $elements = $schemeUnit->elements;

                if ($elements->isEmpty()) {
                    continue;
                }

                // Create 3-8 evidence items per unit
                $evidenceCount = rand(3, min(8, $elements->count() * 2));

                for ($i = 0; $i < $evidenceCount; $i++) {
                    $evidence = Apl02Evidence::create([
                        'assessee_id' => $assessee->id,
                        'apl02_unit_id' => $unit->id,
                        'evidence_type' => $this->randomEvidenceType(),
                        'title' => $this->randomEvidenceTitle(),
                        'description' => 'Sample evidence description for portfolio assessment.',
                        'file_name' => 'sample_evidence_' . rand(1000, 9999) . '.pdf',
                        'file_path' => 'apl02/evidence/sample_' . rand(1000, 9999) . '.pdf',
                        'original_filename' => 'original_file_' . rand(1000, 9999) . '.pdf',
                        'issued_by' => rand(0, 1) ? 'Training Provider XYZ' : 'Professional Organization ABC',
                        'issuer_organization' => rand(0, 1) ? 'National Certification Body' : 'Industry Association',
                        'certificate_number' => rand(0, 1) ? 'CERT-' . rand(10000, 99999) : null,
                        'validity_start_date' => now()->subDays(rand(30, 365)),
                        'validity_end_date' => now()->addDays(rand(30, 730)),
                        'verification_status' => $this->randomVerificationStatus(),
                        'verified_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                        'verified_by' => rand(0, 1) && $assessors->isNotEmpty() ? $assessors->random()->id : null,
                        'assessment_result' => $this->randomAssessmentStatus(),
                        'assessor_notes' => rand(0, 1) ? 'Evidence meets the required criteria.' : null,
                        'is_authentic' => rand(0, 1),
                        'is_current' => rand(0, 1),
                        'is_sufficient' => rand(0, 1),
                        'submitted_at' => rand(0, 1) ? now()->subDays(rand(1, 15)) : null,
                        'notes' => rand(0, 1) ? 'Additional notes for this evidence.' : null,
                    ]);

                    // Map evidence to 1-3 elements
                    $elementsToMap = $elements->random(min(rand(1, 3), $elements->count()));

                    foreach ($elementsToMap as $element) {
                        Apl02EvidenceMap::create([
                            'apl02_evidence_id' => $evidence->id,
                            'scheme_element_id' => $element->id,
                            'coverage_level' => $this->randomCoverageLevel(),
                            'assessor_evaluation' => $this->randomAssessorEvaluation(),
                            'evaluated_by' => rand(0, 1) && $assessors->isNotEmpty() ? $assessors->random()->id : null,
                            'evaluated_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                            'evaluation_notes' => rand(0, 1) ? 'Evidence demonstrates competency in this element.' : null,
                            'display_order' => $i,
                        ]);
                    }
                }

                // Update completion percentage
                $unit->calculateCompletionPercentage();
                $unit->save();

                // Create 0-2 assessor reviews per unit
                $reviewCount = rand(0, 2);

                for ($i = 0; $i < $reviewCount; $i++) {
                    if ($assessors->isEmpty()) {
                        break;
                    }

                    $review = Apl02AssessorReview::create([
                        'apl02_unit_id' => $unit->id,
                        'assessor_id' => $assessors->random()->id,
                        'review_type' => $this->randomReviewType(),
                        'status' => $this->randomReviewStatus(),
                        'decision' => $this->randomReviewDecision(),
                        'validity_score' => rand(60, 100),
                        'authenticity_score' => rand(60, 100),
                        'currency_score' => rand(60, 100),
                        'sufficiency_score' => rand(60, 100),
                        'consistency_score' => rand(60, 100),
                        'overall_comments' => 'The portfolio demonstrates good understanding of the required competencies.',
                        'recommendations' => rand(0, 1) ? 'Continue to develop skills in this area.' : null,
                        'strengths' => [
                            'Clear documentation',
                            'Comprehensive evidence',
                            'Well-organized portfolio'
                        ],
                        'weaknesses' => rand(0, 1) ? [
                            'Some evidence could be more recent',
                            'Need more variety in evidence types'
                        ] : [],
                        'improvement_areas' => rand(0, 1) ? [
                            'Add more recent work samples',
                            'Include certification documents'
                        ] : [],
                        'next_steps' => [
                            'Submit additional evidence',
                            'Schedule interview if required'
                        ],
                        'requires_interview' => rand(0, 1),
                        'requires_demonstration' => rand(0, 1),
                        'interview_notes' => rand(0, 1) ? 'Interview scheduled for further assessment.' : null,
                        'demonstration_notes' => rand(0, 1) ? 'Practical demonstration may be required.' : null,
                        'started_at' => now()->subDays(rand(5, 15)),
                        'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                        'deadline' => now()->addDays(rand(7, 30)),
                        'is_final' => rand(0, 1),
                        'verified_at' => rand(0, 1) ? now()->subDays(rand(1, 3)) : null,
                        'verified_by' => rand(0, 1) && $assessors->count() > 1 ? $assessors->where('id', '!=', $review->assessor_id ?? 0)->random()->id : null,
                        'verification_notes' => rand(0, 1) ? 'Review has been verified and approved.' : null,
                    ]);

                    // Calculate overall score
                    $review->calculateOverallScore();
                    $review->save();
                }
            }
        }

        $unitCount = Apl02Unit::count();
        $evidenceCount = Apl02Evidence::count();
        $mappingCount = Apl02EvidenceMap::count();
        $reviewCount = Apl02AssessorReview::count();

        $this->command->info("Created {$unitCount} portfolio units");
        $this->command->info("Created {$evidenceCount} evidence items");
        $this->command->info("Created {$mappingCount} evidence mappings");
        $this->command->info("Created {$reviewCount} assessor reviews");
    }

    private function randomStatus(): string
    {
        $statuses = [
            'not_started' => 20,
            'in_progress' => 30,
            'submitted' => 20,
            'under_review' => 15,
            'competent' => 5,
            'not_yet_competent' => 5,
            'completed' => 5,
        ];

        return $this->weightedRandom($statuses);
    }

    private function randomAssessmentResult(): string
    {
        $results = [
            'pending' => 60,
            'competent' => 20,
            'not_yet_competent' => 10,
            'requires_more_evidence' => 10,
        ];

        return $this->weightedRandom($results);
    }

    private function randomEvidenceType(): string
    {
        $types = [
            'document' => 30,
            'certificate' => 20,
            'work_sample' => 15,
            'project' => 10,
            'photo' => 10,
            'video' => 5,
            'presentation' => 5,
            'log_book' => 3,
            'portfolio' => 1,
            'other' => 1,
        ];

        return $this->weightedRandom($types);
    }

    private function randomEvidenceTitle(): string
    {
        $titles = [
            'Training Certificate - Advanced Competency',
            'Work Sample - Project Documentation',
            'Professional Certification Document',
            'Evidence of Work Experience',
            'Project Portfolio Sample',
            'Competency Demonstration Video',
            'Workshop Attendance Certificate',
            'Case Study Documentation',
            'Performance Review Document',
            'Skills Assessment Report',
        ];

        return $titles[array_rand($titles)];
    }

    private function randomVerificationStatus(): string
    {
        $statuses = [
            'pending' => 40,
            'verified' => 40,
            'rejected' => 10,
            'requires_clarification' => 10,
        ];

        return $this->weightedRandom($statuses);
    }

    private function randomAssessmentStatus(): string
    {
        $statuses = [
            'pending' => 40,
            'valid' => 35,
            'invalid' => 15,
            'insufficient' => 10,
        ];

        return $this->weightedRandom($statuses);
    }

    private function randomCoverageLevel(): string
    {
        $levels = [
            'full' => 50,
            'partial' => 35,
            'supplementary' => 15,
        ];

        return $this->weightedRandom($levels);
    }

    private function randomAssessorEvaluation(): string
    {
        $evaluations = [
            'pending' => 40,
            'accepted' => 40,
            'rejected' => 10,
            'requires_more_evidence' => 10,
        ];

        return $this->weightedRandom($evaluations);
    }

    private function randomReviewType(): string
    {
        $types = [
            'initial_review' => 40,
            'verification' => 25,
            'validation' => 15,
            'final_assessment' => 15,
            're_assessment' => 5,
        ];

        return $this->weightedRandom($types);
    }

    private function randomReviewStatus(): string
    {
        $statuses = [
            'draft' => 20,
            'in_progress' => 30,
            'completed' => 35,
            'verified' => 10,
            'requires_revision' => 5,
        ];

        return $this->weightedRandom($statuses);
    }

    private function randomReviewDecision(): string
    {
        $decisions = [
            'pending' => 30,
            'competent' => 25,
            'not_yet_competent' => 15,
            'requires_more_evidence' => 15,
            'requires_demonstration' => 10,
            'deferred' => 5,
        ];

        return $this->weightedRandom($decisions);
    }

    /**
     * Get weighted random value
     */
    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        $current = 0;

        foreach ($weights as $value => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $value;
            }
        }

        return array_key_first($weights);
    }
}
