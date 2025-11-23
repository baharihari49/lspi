<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Seeding Assessment Module data...\n";

        // Get required data
        $assessees = DB::table('assessees')->pluck('id')->toArray();
        $schemes = DB::table('schemes')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();
        $events = DB::table('events')->pluck('id')->toArray();
        $tuks = DB::table('tuk')->pluck('id')->toArray();

        if (empty($assessees) || empty($schemes) || empty($users)) {
            echo "⚠ Missing required data. Please run assessees, schemes, and users seeders first.\n";
            return;
        }

        // Create assessments
        $assessments = [];
        $assessmentCount = 0;

        foreach ($assessees as $index => $assesseeId) {
            $schemeId = $schemes[array_rand($schemes)];
            $leadAssessorId = $users[array_rand($users)];
            $eventId = !empty($events) ? $events[array_rand($events)] : null;
            $tukId = !empty($tuks) ? $tuks[array_rand($tuks)] : null;

            $statuses = ['draft', 'scheduled', 'in_progress', 'completed', 'under_review', 'verified', 'approved'];
            $status = $statuses[min($index, count($statuses) - 1)];

            $scheduledDate = Carbon::now()->addDays(rand(1, 30));

            $assessmentId = DB::table('assessments')->insertGetId([
                'assessment_number' => 'ASM-2025-' . str_pad($assessmentCount + 1, 4, '0', STR_PAD_LEFT),
                'title' => 'Assessment for Assessee #' . $assesseeId,
                'description' => 'Comprehensive competency assessment for certification',
                'assessee_id' => $assesseeId,
                'scheme_id' => $schemeId,
                'event_id' => $eventId,
                'lead_assessor_id' => $leadAssessorId,
                'assessment_method' => ['portfolio', 'observation', 'interview', 'demonstration', 'mixed'][array_rand(['portfolio', 'observation', 'interview', 'demonstration', 'mixed'])],
                'assessment_type' => ['initial', 'verification', 'surveillance', 're_assessment'][array_rand(['initial', 'verification', 'surveillance', 're_assessment'])],
                'scheduled_date' => $scheduledDate->format('Y-m-d'),
                'scheduled_time' => '09:00:00',
                'venue' => ['LSP Office', 'Online', 'Client Site', 'Training Center'][array_rand(['LSP Office', 'Online', 'Client Site', 'Training Center'])],
                'tuk_id' => $tukId,
                'status' => $status,
                'started_at' => $status !== 'draft' && $status !== 'scheduled' ? $scheduledDate : null,
                'completed_at' => in_array($status, ['completed', 'under_review', 'verified', 'approved']) ? $scheduledDate->copy()->addHours(3) : null,
                'duration_minutes' => in_array($status, ['completed', 'under_review', 'verified', 'approved']) ? rand(120, 300) : null,
                'planned_duration_minutes' => 180,
                'overall_result' => in_array($status, ['verified', 'approved']) ? ['competent', 'not_yet_competent'][array_rand(['competent', 'not_yet_competent'])] : 'pending',
                'overall_score' => in_array($status, ['completed', 'under_review', 'verified', 'approved']) ? rand(7000, 9500) / 100 : null,
                'notes' => 'Assessment scheduled and prepared',
                'created_by' => $leadAssessorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $assessments[] = [
                'id' => $assessmentId,
                'scheme_id' => $schemeId,
                'assessee_id' => $assesseeId,
                'status' => $status,
                'lead_assessor_id' => $leadAssessorId,
            ];

            $assessmentCount++;
        }

        echo "Created {$assessmentCount} assessments\n";

        // Create assessment units for each assessment
        $totalUnits = 0;
        $totalCriteria = 0;

        foreach ($assessments as $assessment) {
            // Get units for this scheme through scheme_versions
            $schemeVersion = DB::table('scheme_versions')
                ->where('scheme_id', $assessment['scheme_id'])
                ->where('is_current', true)
                ->first();

            // If no current version, get the latest one
            if (!$schemeVersion) {
                $schemeVersion = DB::table('scheme_versions')
                    ->where('scheme_id', $assessment['scheme_id'])
                    ->orderBy('version', 'desc')
                    ->first();
            }

            if (!$schemeVersion) {
                continue;
            }

            $units = DB::table('scheme_units')
                ->where('scheme_version_id', $schemeVersion->id)
                ->get();

            if ($units->isEmpty()) {
                continue;
            }

            foreach ($units as $unitIndex => $unit) {
                $assessorId = $users[array_rand($users)];
                $unitStatus = $this->getUnitStatus($assessment['status']);

                $assessmentUnitId = DB::table('assessment_units')->insertGetId([
                    'assessment_id' => $assessment['id'],
                    'scheme_unit_id' => $unit->id,
                    'assessor_id' => $assessorId,
                    'unit_code' => $unit->code,
                    'unit_title' => $unit->title,
                    'unit_description' => $unit->description,
                    'assessment_method' => ['portfolio', 'observation', 'interview', 'demonstration', 'mixed'][array_rand(['portfolio', 'observation', 'interview', 'demonstration', 'mixed'])],
                    'status' => $unitStatus,
                    'score' => in_array($unitStatus, ['completed', 'competent']) ? rand(7000, 9500) / 100 : null,
                    'elements_passed' => 0,
                    'total_elements' => 0,
                    'completion_percentage' => in_array($unitStatus, ['completed', 'competent']) ? rand(7000, 10000) / 100 : 0,
                    'result' => in_array($unitStatus, ['competent']) ? 'competent' : (in_array($unitStatus, ['not_yet_competent']) ? 'not_yet_competent' : 'pending'),
                    'started_at' => in_array($unitStatus, ['in_progress', 'completed', 'competent']) ? now()->subDays(rand(1, 5)) : null,
                    'completed_at' => in_array($unitStatus, ['completed', 'competent']) ? now()->subDays(rand(0, 2)) : null,
                    'duration_minutes' => in_array($unitStatus, ['completed', 'competent']) ? rand(60, 180) : null,
                    'display_order' => $unitIndex,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalUnits++;

                // Create assessment criteria for each element
                $elements = DB::table('scheme_elements')
                    ->where('scheme_unit_id', $unit->id)
                    ->get();

                foreach ($elements as $elementIndex => $element) {
                    $criteriaResult = $this->getCriteriaResult($unitStatus);

                    DB::table('assessment_criteria')->insert([
                        'assessment_unit_id' => $assessmentUnitId,
                        'scheme_element_id' => $element->id,
                        'element_code' => $element->code,
                        'element_title' => $element->title,
                        'assessment_method' => ['portfolio', 'observation', 'interview', 'demonstration'][array_rand(['portfolio', 'observation', 'interview', 'demonstration'])],
                        'result' => $criteriaResult,
                        'score' => $criteriaResult === 'competent' ? rand(8000, 10000) / 100 : ($criteriaResult === 'not_yet_competent' ? rand(5000, 7000) / 100 : null),
                        'is_critical' => rand(0, 100) < 30, // 30% are critical
                        'evidence_observed' => $criteriaResult !== 'pending' ? 'Evidence demonstrated through ' . ['portfolio review', 'direct observation', 'interview', 'practical demonstration'][array_rand(['portfolio review', 'direct observation', 'interview', 'practical demonstration'])] : null,
                        'assessor_notes' => $criteriaResult !== 'pending' ? 'Candidate demonstrated ' . ($criteriaResult === 'competent' ? 'excellent' : 'limited') . ' understanding of this element.' : null,
                        'assessed_at' => $criteriaResult !== 'pending' ? now()->subDays(rand(0, 3)) : null,
                        'assessed_by' => $criteriaResult !== 'pending' ? $assessorId : null,
                        'display_order' => $elementIndex,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $totalCriteria++;
                }
            }
        }

        echo "Created {$totalUnits} assessment units\n";
        echo "Created {$totalCriteria} assessment criteria\n";

        // Create observations for some assessments
        $observationCount = 0;
        foreach ($assessments as $assessment) {
            if (!in_array($assessment['status'], ['in_progress', 'completed', 'under_review', 'verified', 'approved'])) {
                continue;
            }

            $assessmentUnits = DB::table('assessment_units')
                ->where('assessment_id', $assessment['id'])
                ->limit(2)
                ->get();

            foreach ($assessmentUnits as $unit) {
                DB::table('assessment_observations')->insert([
                    'assessment_unit_id' => $unit->id,
                    'observer_id' => $assessment['lead_assessor_id'],
                    'observation_number' => 'OBS-' . str_pad($observationCount + 1, 4, '0', STR_PAD_LEFT),
                    'activity_observed' => 'Practical demonstration of ' . $unit->unit_title,
                    'context' => 'Direct observation during practical assessment session',
                    'observed_at' => now()->subDays(rand(1, 5)),
                    'duration_minutes' => rand(30, 90),
                    'location' => ['LSP Office', 'Workshop', 'Lab', 'Client Site'][array_rand(['LSP Office', 'Workshop', 'Lab', 'Client Site'])],
                    'what_was_observed' => 'Candidate successfully demonstrated the required competencies. Performance was consistent and met the required standards.',
                    'performance_indicators' => 'All key performance indicators were met satisfactorily.',
                    'evidence_collected' => 'Photos, video recording, completed work samples',
                    'competency_demonstrated' => ['fully_competent', 'partially_competent'][array_rand(['fully_competent', 'partially_competent'])],
                    'score' => rand(7500, 9500) / 100,
                    'strengths' => 'Good technical skills, attention to detail, follows safety procedures',
                    'areas_for_improvement' => rand(0, 1) ? 'Time management could be improved' : null,
                    'observer_notes' => 'Candidate showed good understanding and practical application of skills.',
                    'requires_follow_up' => rand(0, 100) < 20,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $observationCount++;
            }
        }

        echo "Created {$observationCount} observations\n";

        // Create documents
        $documentCount = 0;
        foreach ($assessments as $assessment) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                DB::table('assessment_documents')->insert([
                    'assessment_id' => $assessment['id'],
                    'uploaded_by' => $assessment['assessee_id'],
                    'document_number' => 'DOC-' . str_pad($documentCount + 1, 4, '0', STR_PAD_LEFT),
                    'title' => ['Work Sample', 'Certificate', 'Portfolio', 'Training Record'][$i % 4],
                    'description' => 'Supporting evidence for competency assessment',
                    'document_type' => ['evidence', 'supporting_document', 'work_sample', 'certificate'][$i % 4],
                    'evidence_type' => ['direct', 'indirect', 'supplementary'][array_rand(['direct', 'indirect', 'supplementary'])],
                    'file_path' => 'assessments/documents/doc_' . ($documentCount + 1) . '.pdf',
                    'file_name' => 'document_' . ($documentCount + 1) . '.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => rand(100000, 5000000),
                    'original_filename' => 'original_document.pdf',
                    'verification_status' => ['pending', 'verified', 'verified', 'verified'][array_rand(['pending', 'verified', 'verified', 'verified'])],
                    'verified_by' => rand(0, 1) ? $assessment['lead_assessor_id'] : null,
                    'verified_at' => rand(0, 1) ? now()->subDays(rand(0, 3)) : null,
                    'relevance' => ['highly_relevant', 'relevant'][array_rand(['highly_relevant', 'relevant'])],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $documentCount++;
            }
        }

        echo "Created {$documentCount} documents\n";

        // Create interviews
        $interviewCount = 0;
        foreach ($assessments as $assessment) {
            if (!in_array($assessment['status'], ['in_progress', 'completed', 'under_review', 'verified', 'approved'])) {
                continue;
            }

            $assessmentUnits = DB::table('assessment_units')
                ->where('assessment_id', $assessment['id'])
                ->limit(1)
                ->get();

            foreach ($assessmentUnits as $unit) {
                $questions = [
                    [
                        'question' => 'Explain your understanding of the key concepts in ' . $unit->unit_title,
                        'answer' => 'I understand the key concepts and can apply them in practical situations...',
                        'competency_area' => 'Technical Knowledge',
                        'satisfactory' => true,
                        'notes' => 'Clear understanding demonstrated'
                    ],
                    [
                        'question' => 'Describe a situation where you applied these skills',
                        'answer' => 'In my previous project, I successfully applied these skills to...',
                        'competency_area' => 'Practical Application',
                        'satisfactory' => true,
                        'notes' => 'Good practical examples provided'
                    ]
                ];

                DB::table('assessment_interviews')->insert([
                    'assessment_unit_id' => $unit->id,
                    'interviewer_id' => $assessment['lead_assessor_id'],
                    'interviewee_id' => $assessment['assessee_id'],
                    'interview_number' => 'INT-' . str_pad($interviewCount + 1, 4, '0', STR_PAD_LEFT),
                    'session_title' => 'Technical Interview - ' . $unit->unit_title,
                    'purpose' => 'Verify understanding and knowledge of competency requirements',
                    'conducted_at' => now()->subDays(rand(1, 5)),
                    'duration_minutes' => rand(30, 60),
                    'location' => ['LSP Office', 'Online - Zoom', 'Client Site'][array_rand(['LSP Office', 'Online - Zoom', 'Client Site'])],
                    'interview_method' => ['face_to_face', 'video_conference'][array_rand(['face_to_face', 'video_conference'])],
                    'questions' => json_encode($questions),
                    'key_findings' => 'Candidate demonstrated good understanding of theoretical concepts and practical applications',
                    'competencies_demonstrated' => 'Technical knowledge, problem-solving, communication skills',
                    'overall_assessment' => ['fully_satisfactory', 'satisfactory'][array_rand(['fully_satisfactory', 'satisfactory'])],
                    'score' => rand(7500, 9500) / 100,
                    'interviewer_notes' => 'Professional demeanor, good communication, clear explanations',
                    'behavioral_observations' => 'Confident, articulate, demonstrates enthusiasm for the field',
                    'technical_observations' => 'Strong technical foundation, able to explain complex concepts clearly',
                    'requires_follow_up' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $interviewCount++;
            }
        }

        echo "Created {$interviewCount} interviews\n";

        // Create verification records for completed assessments
        $verificationCount = 0;
        foreach ($assessments as $assessment) {
            if (!in_array($assessment['status'], ['under_review', 'verified', 'approved'])) {
                continue;
            }

            $checklist = [
                ['item' => 'All evidence documented', 'checked' => true, 'notes' => 'Complete'],
                ['item' => 'Assessment standards met', 'checked' => true, 'notes' => 'Meets requirements'],
                ['item' => 'Assessment decisions consistent', 'checked' => true, 'notes' => 'Consistent with standards'],
                ['item' => 'Documentation complete', 'checked' => true, 'notes' => 'All documents present'],
            ];

            DB::table('assessment_verification')->insert([
                'assessment_id' => $assessment['id'],
                'verifier_id' => $users[array_rand($users)],
                'verification_number' => 'VER-' . str_pad($verificationCount + 1, 4, '0', STR_PAD_LEFT),
                'verification_level' => 'assessment_level',
                'verification_type' => ['internal', 'external'][array_rand(['internal', 'external'])],
                'checklist' => json_encode($checklist),
                'verification_status' => in_array($assessment['status'], ['verified', 'approved']) ? 'approved' : 'in_progress',
                'verification_result' => in_array($assessment['status'], ['verified', 'approved']) ? 'satisfactory' : null,
                'findings' => 'Assessment conducted according to standards and requirements',
                'strengths' => 'Thorough documentation, clear evidence trail',
                'meets_standards' => true,
                'evidence_sufficient' => true,
                'assessment_fair' => true,
                'documentation_complete' => true,
                'verified_at' => in_array($assessment['status'], ['verified', 'approved']) ? now()->subDays(rand(0, 2)) : null,
                'verification_duration_minutes' => rand(60, 120),
                'verifier_notes' => 'Assessment meets all quality standards',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $verificationCount++;
        }

        echo "Created {$verificationCount} verification records\n";

        // Create feedback for assessments
        $feedbackCount = 0;
        foreach ($assessments as $assessment) {
            if (!in_array($assessment['status'], ['completed', 'under_review', 'verified', 'approved'])) {
                continue;
            }

            DB::table('assessment_feedback')->insert([
                'assessment_id' => $assessment['id'],
                'provider_id' => $assessment['lead_assessor_id'],
                'recipient_id' => $assessment['assessee_id'],
                'feedback_number' => 'FB-' . str_pad($feedbackCount + 1, 4, '0', STR_PAD_LEFT),
                'title' => 'Assessment Feedback',
                'feedback_type' => in_array($assessment['status'], ['verified', 'approved']) ? 'summative' : 'formative',
                'feedback_method' => 'written',
                'positive_aspects' => 'Strong performance overall. Demonstrated good understanding of key concepts. Practical skills well developed.',
                'areas_for_improvement' => 'Consider developing time management skills. Could benefit from additional practice in complex scenarios.',
                'specific_examples' => 'Your portfolio documentation was excellent. The practical demonstration showed good technique.',
                'recommendations' => 'Continue developing your skills through regular practice. Consider advanced training in specialized areas.',
                'strengths' => json_encode(['Technical knowledge', 'Practical skills', 'Professional demeanor', 'Documentation quality']),
                'weaknesses' => json_encode(['Time management', 'Advanced problem-solving']),
                'development_areas' => json_encode(['Advanced techniques', 'Leadership skills']),
                'provided_at' => now()->subDays(rand(0, 2)),
                'delivered_at' => now()->subDays(rand(0, 1)),
                'is_confidential' => false,
                'acknowledged_at' => rand(0, 1) ? now() : null,
                'requires_follow_up' => rand(0, 100) < 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $feedbackCount++;
        }

        echo "Created {$feedbackCount} feedback records\n";

        // Create results for approved assessments
        $resultCount = 0;
        foreach ($assessments as $assessment) {
            if (!in_array($assessment['status'], ['approved'])) {
                continue;
            }

            $finalResult = ['competent', 'not_yet_competent'][array_rand(['competent', 'competent', 'not_yet_competent'])]; // 66% competent

            $resultId = DB::table('assessment_results')->insertGetId([
                'assessment_id' => $assessment['id'],
                'assessee_id' => $assessment['assessee_id'],
                'scheme_id' => $assessment['scheme_id'],
                'result_number' => 'RES-2025-' . str_pad($resultCount + 1, 4, '0', STR_PAD_LEFT),
                'certificate_number' => $finalResult === 'competent' ? 'CERT-2025-' . str_pad($resultCount + 1, 4, '0', STR_PAD_LEFT) : null,
                'final_result' => $finalResult,
                'overall_score' => rand(7000, 9500) / 100,
                'units_assessed' => rand(3, 5),
                'units_competent' => $finalResult === 'competent' ? rand(3, 5) : rand(1, 2),
                'units_not_yet_competent' => $finalResult === 'not_yet_competent' ? rand(1, 3) : 0,
                'total_criteria' => rand(15, 25),
                'criteria_met' => $finalResult === 'competent' ? rand(15, 25) : rand(8, 14),
                'criteria_percentage' => $finalResult === 'competent' ? rand(8500, 10000) / 100 : rand(6000, 8000) / 100,
                'critical_criteria_total' => rand(5, 8),
                'critical_criteria_met' => $finalResult === 'competent' ? rand(5, 8) : rand(2, 4),
                'all_critical_criteria_met' => $finalResult === 'competent',
                'executive_summary' => $finalResult === 'competent'
                    ? 'The candidate has successfully demonstrated competence across all required units and criteria. Performance was consistent and met professional standards.'
                    : 'The candidate has demonstrated competence in several areas but requires additional evidence in some key criteria before competency can be confirmed.',
                'key_strengths' => json_encode(['Technical knowledge', 'Practical skills', 'Professional approach', 'Documentation']),
                'development_areas' => json_encode($finalResult === 'not_yet_competent' ? ['Advanced techniques', 'Complex problem solving'] : []),
                'overall_performance_notes' => 'Solid performance throughout the assessment process.',
                'documents_submitted' => rand(3, 6),
                'observations_conducted' => rand(1, 3),
                'interviews_conducted' => rand(1, 2),
                'evidence_summary' => 'Comprehensive evidence portfolio including work samples, observations, and interview records.',
                'recommendations' => json_encode($finalResult === 'competent'
                    ? ['Maintain certification through CPD', 'Consider advanced certifications']
                    : ['Complete additional training', 'Resubmit portfolio evidence', 'Schedule reassessment']),
                'next_steps' => $finalResult === 'competent' ? 'Certificate will be issued within 14 days' : 'Review feedback and submit additional evidence',
                'reassessment_plan' => $finalResult === 'not_yet_competent' ? 'Focus on developing skills in identified gap areas. Reassessment can be scheduled after 3 months.' : null,
                'certification_date' => $finalResult === 'competent' ? now()->format('Y-m-d') : null,
                'certification_expiry_date' => $finalResult === 'competent' ? now()->addYears(3)->format('Y-m-d') : null,
                'certificate_issued' => $finalResult === 'competent' ? rand(0, 1) : false,
                'certificate_issued_at' => $finalResult === 'competent' && rand(0, 1) ? now() : null,
                'lead_assessor_id' => $assessment['lead_assessor_id'],
                'contributing_assessors' => json_encode([$users[array_rand($users)]]),
                'approval_status' => 'approved',
                'approved_by' => $users[array_rand($users)],
                'approved_at' => now(),
                'is_published' => true,
                'published_at' => now(),
                'published_by' => $users[array_rand($users)],
                'assessee_notified' => true,
                'assessee_notified_at' => now(),
                'is_valid' => true,
                'created_by' => $assessment['lead_assessor_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create approval records
            DB::table('result_approval')->insert([
                'assessment_result_id' => $resultId,
                'approver_id' => $users[array_rand($users)],
                'approval_level' => 1,
                'sequence_order' => 1,
                'approver_role' => 'lead_assessor',
                'status' => 'approved',
                'decision' => 'approve',
                'comments' => 'Assessment meets all requirements. Recommend approval.',
                'checklist' => json_encode([
                    ['item' => 'All evidence complete', 'checked' => true, 'notes' => 'Complete'],
                    ['item' => 'Standards met', 'checked' => true, 'notes' => 'Meets requirements'],
                ]),
                'assigned_at' => now()->subDays(2),
                'reviewed_at' => now()->subDays(1),
                'decision_at' => now()->subDays(1),
                'review_duration_minutes' => rand(30, 90),
                'approver_notified' => true,
                'approver_notified_at' => now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $resultCount++;
        }

        echo "Created {$resultCount} assessment results\n";
        echo "Created {$resultCount} approval records\n";

        echo "\n✓ Assessment Module seeding completed successfully!\n";
        echo "Summary:\n";
        echo "  - {$assessmentCount} assessments\n";
        echo "  - {$totalUnits} assessment units\n";
        echo "  - {$totalCriteria} assessment criteria\n";
        echo "  - {$observationCount} observations\n";
        echo "  - {$documentCount} documents\n";
        echo "  - {$interviewCount} interviews\n";
        echo "  - {$verificationCount} verifications\n";
        echo "  - {$feedbackCount} feedback records\n";
        echo "  - {$resultCount} results with approvals\n";
    }

    private function getUnitStatus($assessmentStatus)
    {
        if (in_array($assessmentStatus, ['draft', 'scheduled'])) {
            return 'pending';
        }
        if ($assessmentStatus === 'in_progress') {
            return ['in_progress', 'pending'][array_rand(['in_progress', 'pending'])];
        }
        if (in_array($assessmentStatus, ['completed', 'under_review'])) {
            return ['completed', 'in_progress'][array_rand(['completed', 'in_progress'])];
        }
        if (in_array($assessmentStatus, ['verified', 'approved'])) {
            return ['competent', 'not_yet_competent'][array_rand(['competent', 'competent', 'not_yet_competent'])];
        }
        return 'pending';
    }

    private function getCriteriaResult($unitStatus)
    {
        if (in_array($unitStatus, ['pending'])) {
            return 'pending';
        }
        if (in_array($unitStatus, ['in_progress'])) {
            return ['pending', 'competent'][array_rand(['pending', 'competent'])];
        }
        if (in_array($unitStatus, ['completed', 'competent'])) {
            return ['competent', 'competent', 'not_yet_competent'][array_rand(['competent', 'competent', 'not_yet_competent'])];
        }
        if ($unitStatus === 'not_yet_competent') {
            return ['not_yet_competent', 'competent'][array_rand(['not_yet_competent', 'competent'])];
        }
        return 'pending';
    }
}
