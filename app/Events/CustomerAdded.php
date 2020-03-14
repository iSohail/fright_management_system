<?php

namespace App\Events;

use App\Customer;
use App\Http\Resources\CustomerResource;

// use App\Bilty;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $customers;
    public function __construct()
    {
        $customers_data = Customer::with('bilties')->get();
        CustomerResource::withoutWrapping();
        $this->customers = CustomerResource::collection($customers_data);
        // $this->customers = Customer::with('bilties')->get();
        // $this->customers = Bilty::with(['customer', 'challan', 'packages'])->get();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('customers');
    }
}
