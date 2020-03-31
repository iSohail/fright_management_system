<?php

namespace App\Events;

use App\Bilty;
use App\Http\Resources\BiltyResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BiltyAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $bilties;
    public function __construct()
    {
        $bilties_data = Bilty::with(['customer', 'challan', 'packages'])->get();
        BiltyResource::withoutWrapping();
        $this->bilties = BiltyResource::collection($bilties_data);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('bilties');
    }
}
