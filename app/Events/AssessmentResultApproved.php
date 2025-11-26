<?php

namespace App\Events;

use App\Models\Assessment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssessmentResultApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Assessment $assessment;

    /**
     * Create a new event instance.
     */
    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }
}
