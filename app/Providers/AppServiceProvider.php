<?php

namespace App\Providers;

use App\Events\Apl01Approved;
use App\Events\Apl02AllUnitsCompetent;
use App\Events\AssessmentResultApproved;
use App\Listeners\GenerateApl02FromApprovedApl01;
use App\Listeners\GenerateCertificateFromApprovedAssessment;
use App\Listeners\ScheduleAssessmentFromCompletedApl02;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Certification Flow Event Listeners
        Event::listen(
            Apl01Approved::class,
            GenerateApl02FromApprovedApl01::class
        );

        Event::listen(
            Apl02AllUnitsCompetent::class,
            ScheduleAssessmentFromCompletedApl02::class
        );

        Event::listen(
            AssessmentResultApproved::class,
            GenerateCertificateFromApprovedAssessment::class
        );
    }
}
