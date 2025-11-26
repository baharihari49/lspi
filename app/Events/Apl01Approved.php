<?php

namespace App\Events;

use App\Models\Apl01Form;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Apl01Approved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Apl01Form $apl01Form;

    /**
     * Create a new event instance.
     */
    public function __construct(Apl01Form $apl01Form)
    {
        $this->apl01Form = $apl01Form;
    }
}
