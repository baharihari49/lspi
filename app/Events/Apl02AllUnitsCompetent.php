<?php

namespace App\Events;

use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Apl02AllUnitsCompetent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Apl01Form $apl01Form;
    public Apl02Unit $lastUnit;

    /**
     * Create a new event instance.
     */
    public function __construct(Apl01Form $apl01Form, Apl02Unit $lastUnit)
    {
        $this->apl01Form = $apl01Form;
        $this->lastUnit = $lastUnit;
    }
}
