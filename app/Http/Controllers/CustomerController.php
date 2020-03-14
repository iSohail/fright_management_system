<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\CustomerAdded;
use App\Http\Resources\CustomerResource;
use App\Receiver;
use App\Sender;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //only senders can store the bilty
        $this->middleware('isSender')->only('store');
        //only admin can delete bilty
        $this->middleware('isAdmin')->only('destroy');
    }
    public function index()
    {
        $customers = Customer::with('bilties')->get();
        CustomerResource::withoutWrapping();
        return CustomerResource::collection($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $customer = new Customer;

        $customer->name = $data['customerName'];
        $customer->cell_no = $data['cellNo'];
        $customer->customer_no = $data['customerNo'];
        $customer->per_kg_rate = $data['perKg'];
        $customer->per_cbm_rate = $data['perCbm'];
        $customer->per_pck_rate = $data['perPckg'];
        $customer->company = $data['company'];

        if ($customer->save()) {
            $customer = Customer::findOrFail($customer->id);
            // return $customer;
            $sender = new Sender;
            $sender->name = $data['sender'];
            $sender->address = $data['sender_address'];
            $sender->customer()->associate($customer);

            $sender->save();

            $receiver = new Receiver;
            $receiver->name = $data['receiver'];
            $receiver->address = $data['receiver_address'];
            $receiver->customer()->associate($customer);

            $receiver->save();
            // return 'sdfasdfas';

            event(new CustomerAdded());

            CustomerResource::withoutWrapping();
            return new CustomerResource($customer);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'error saving bilty',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        CustomerResource::withoutWrapping();
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // problem with data fetch
        $data = json_decode($request->getContent(), true);

        // return $data['name'];
        $customer = Customer::findOrFail($data['id']);

        // return $customer;

        $customer->name = $data['name'];
        $customer->cell_no = $data['cellNo'];
        $customer->customer_no = $data['no'];
        $customer->per_kg_rate = $data['perKg'];
        $customer->per_cbm_rate = $data['perCbm'];
        $customer->per_pck_rate = $data['perPackage'];

        if ($customer->save()) {
            CustomerResource::withoutWrapping();
            return new CustomerResource($customer);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'error saving bilty',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        return $customer;
        if ($customer) {
            if ($customer->delete()) {
                CustomerResource::withoutWrapping();
                return new CustomerResource($customer);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
        }
    }

    public function last()
    {
        $customer = Customer::orderby('customer_no', 'desc')->first();
        if ($customer) {
            return $customer['customer_no'] + 1;
        }

        // default value for starting customer number
        return 1000;
    }
}
