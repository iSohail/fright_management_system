<?php

namespace App\Events;

use App\GeneralLedger;
use App\Http\Resources\GeneralLedgerResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LedgerAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $ledgers;
    public function __construct()
    {
        $ledgers = GeneralLedger::with('bilties')->get();
        GeneralLedgerResource::withoutWrapping();
        $this->ledgers = GeneralLedgerResource::collection($ledgers);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ledgers');
    }
}
