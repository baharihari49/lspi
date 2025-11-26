<?php

namespace App\Services;

use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use App\Models\Assessment;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Assessee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class FlowNotificationService
{
    /**
     * Notify when APL-02 is generated from approved APL-01.
     */
    public function notifyApl02Generated(Apl01Form $apl01, array $generatedUnits): void
    {
        $assessee = $apl01->assessee;

        if (!$assessee) {
            Log::warning('Cannot send APL-02 notification: No assessee found for APL-01 #' . $apl01->id);
            return;
        }

        $message = "APL-02 (Asesmen Mandiri) Anda telah dibuat dengan " . count($generatedUnits) . " unit kompetensi. " .
                   "Silakan login untuk melengkapi asesmen mandiri Anda.";

        $this->createNotification($assessee, [
            'type' => 'apl02_generated',
            'title' => 'APL-02 Asesmen Mandiri Dibuat',
            'message' => $message,
            'data' => [
                'apl01_id' => $apl01->id,
                'form_number' => $apl01->form_number,
                'unit_count' => count($generatedUnits),
            ],
        ]);

        // Send email notification if email available
        if ($assessee->email) {
            $this->sendEmailNotification($assessee->email, 'APL-02 Asesmen Mandiri Dibuat', $message);
        }

        Log::info('APL-02 generated notification sent', [
            'apl01_id' => $apl01->id,
            'assessee_id' => $assessee->id,
            'units_count' => count($generatedUnits),
        ]);
    }

    /**
     * Notify when assessment is scheduled.
     */
    public function notifyAssessmentScheduled(Assessment $assessment): void
    {
        $assessee = $assessment->assessee;

        if (!$assessee) {
            Log::warning('Cannot send assessment notification: No assessee found for Assessment #' . $assessment->id);
            return;
        }

        $scheduledDate = $assessment->scheduled_date?->format('d F Y') ?? 'TBD';
        $venue = $assessment->venue ?? 'Akan diinformasikan';

        $message = "Asesmen Anda telah dijadwalkan pada tanggal {$scheduledDate} " .
                   "di {$venue}. Mohon persiapkan diri Anda dengan baik.";

        $this->createNotification($assessee, [
            'type' => 'assessment_scheduled',
            'title' => 'Jadwal Asesmen',
            'message' => $message,
            'data' => [
                'assessment_id' => $assessment->id,
                'assessment_number' => $assessment->assessment_number,
                'scheduled_date' => $assessment->scheduled_date?->toIso8601String(),
                'venue' => $assessment->venue,
            ],
        ]);

        // Send email notification
        if ($assessee->email) {
            $this->sendEmailNotification($assessee->email, 'Jadwal Asesmen Sertifikasi', $message);
        }

        // Notify lead assessor
        if ($assessment->lead_assessor_id) {
            $assessor = User::find($assessment->lead_assessor_id);
            if ($assessor) {
                $assessorMessage = "Anda ditugaskan sebagai asesor utama untuk asesmen {$assessment->assessment_number} " .
                                  "pada tanggal {$scheduledDate}.";

                $this->createUserNotification($assessor, [
                    'type' => 'assessor_assigned',
                    'title' => 'Penugasan Asesor',
                    'message' => $assessorMessage,
                    'data' => [
                        'assessment_id' => $assessment->id,
                        'assessment_number' => $assessment->assessment_number,
                    ],
                ]);
            }
        }

        Log::info('Assessment scheduled notification sent', [
            'assessment_id' => $assessment->id,
            'assessee_id' => $assessee->id,
        ]);
    }

    /**
     * Notify when certificate is issued.
     */
    public function notifyCertificateIssued(Certificate $certificate): void
    {
        $assessee = $certificate->assessee;

        if (!$assessee) {
            Log::warning('Cannot send certificate notification: No assessee found for Certificate #' . $certificate->id);
            return;
        }

        $validUntil = $certificate->valid_until?->format('d F Y') ?? 'N/A';

        $message = "Selamat! Sertifikat kompetensi Anda ({$certificate->certificate_number}) telah diterbitkan. " .
                   "Sertifikat berlaku hingga {$validUntil}. Anda dapat mengunduh sertifikat melalui portal.";

        $this->createNotification($assessee, [
            'type' => 'certificate_issued',
            'title' => 'Sertifikat Diterbitkan',
            'message' => $message,
            'data' => [
                'certificate_id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'scheme_name' => $certificate->scheme_name,
                'valid_until' => $certificate->valid_until?->toIso8601String(),
            ],
        ]);

        // Send email notification
        if ($assessee->email) {
            $this->sendEmailNotification($assessee->email, 'Sertifikat Kompetensi Diterbitkan', $message);
        }

        Log::info('Certificate issued notification sent', [
            'certificate_id' => $certificate->id,
            'assessee_id' => $assessee->id,
        ]);
    }

    /**
     * Notify when APL-02 unit is reviewed.
     */
    public function notifyApl02UnitReviewed(Apl02Unit $unit): void
    {
        $assessee = $unit->assessee;

        if (!$assessee) {
            return;
        }

        $resultLabel = $unit->assessment_result === 'competent' ? 'Kompeten' : 'Belum Kompeten';

        $message = "Unit kompetensi {$unit->unit_code} - {$unit->unit_title} " .
                   "telah direview dengan hasil: {$resultLabel}.";

        $this->createNotification($assessee, [
            'type' => 'apl02_unit_reviewed',
            'title' => 'Review Unit APL-02',
            'message' => $message,
            'data' => [
                'unit_id' => $unit->id,
                'unit_code' => $unit->unit_code,
                'result' => $unit->assessment_result,
            ],
        ]);

        Log::info('APL-02 unit reviewed notification sent', [
            'unit_id' => $unit->id,
            'assessee_id' => $assessee->id,
        ]);
    }

    /**
     * Create notification for assessee.
     */
    protected function createNotification(Assessee $assessee, array $data): void
    {
        try {
            // Check if Notification model exists
            if (class_exists('\App\Models\Notification')) {
                \App\Models\Notification::create([
                    'notifiable_type' => Assessee::class,
                    'notifiable_id' => $assessee->id,
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'message' => $data['message'],
                    'data' => $data['data'] ?? [],
                    'read_at' => null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
        }
    }

    /**
     * Create notification for user (admin/assessor).
     */
    protected function createUserNotification(User $user, array $data): void
    {
        try {
            if (class_exists('\App\Models\Notification')) {
                \App\Models\Notification::create([
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'message' => $data['message'],
                    'data' => $data['data'] ?? [],
                    'read_at' => null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create user notification: ' . $e->getMessage());
        }
    }

    /**
     * Send email notification.
     */
    protected function sendEmailNotification(string $email, string $subject, string $message): void
    {
        try {
            // Check if EmailLog model exists for logging
            if (class_exists('\App\Models\EmailLog')) {
                \App\Models\EmailLog::create([
                    'to_email' => $email,
                    'subject' => $subject,
                    'body' => $message,
                    'status' => 'queued',
                ]);
            }

            // In production, you would send actual email here
            // Mail::to($email)->queue(new CertificationFlowMail($subject, $message));

            Log::info('Email notification queued', [
                'to' => $email,
                'subject' => $subject,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
    }
}
