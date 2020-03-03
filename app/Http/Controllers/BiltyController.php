<?php

namespace App\Http\Controllers;

use App\Bilty;
use App\Challan;
use App\Customer;
use App\Package;
use App\Http\Resources\BiltyResource;
use Illuminate\Http\Request;

class BiltyController extends Controller
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
        $this->middleware('CheckIsAdmin')->only('destroy');
    }

    public function index()
    {
        $bilties = Bilty::with(['customer', 'challan', 'packages'])->get();
        BiltyResource::withoutWrapping();
        return BiltyResource::collection($bilties);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'status' => 'success to create route',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // converting JSON object into PHP code
        $data = json_decode($request->getContent(), true);

        // return $data[0]['id'];
        if (Bilty::find($data[0]['id'])){
            return response()->json([
                'status' => 'error',
                'message' => 'error saving bilty, already exists',
            ], 500); 

        }

        $bilty = new Bilty;
        $customer = Customer::findOrFail($data[0]['customer_id']);
        $challan = Challan::findOrFail($data[0]['challan_id']);

        $bilty->id = $data[0]['id'];
        $bilty->bilty_no = $data[0]['bilty_no'];
        $bilty->lg_bl_no = $data[0]['lg_bl_no'];
        $bilty->from = $data[0]['from'];
        $bilty->to = $data[0]['to'];
        $bilty->sender = $data[0]['sender'];
        $bilty->receiver = $data[0]['receiver'];
        $bilty->receiver_address = $data[0]['receiver_address'];
        $bilty->status = $data[0]['status'];
        $bilty->payment_status = $data[0]['payment_status'];
        $bilty->bilty_charges = $data[0]['bilty_charges'];
        $bilty->local_charges = $data[0]['local_charges'];

        // $packages=['1','2','3'];

        if ($bilty->save()) {
            $bilty = Bilty::find($data[0]['id']);
            $bilty->customer()->associate($customer);
            $bilty->challan()->associate($challan);

            foreach ($data[0]['packages'] as $val) {
                $package = new Package(['id' => $val["id"], 'package_no' => $val["package_no"], 'description' => $val['description'], 'unit' => $val['unit'], 'quantity' => $val['quantity'], 'total_weight' => $val['total_weight'], 'rent' => $val['rent'], 'labour' => $val['labour']]);
                $bilty->packages()->save($package);
            }

            BiltyResource::withoutWrapping();
            return new BiltyResource($bilty);
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
        $bilty = Bilty::findOrFail($id);
        if ($bilty) {
            BiltyResource::withoutWrapping();
            return new BiltyResource($bilty);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
        }
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
        $bilty = Bilty::findOrFail($id);
        if ($bilty) {
            $customer = Customer::findOrFail($request->get('customer_id'));
            $challan = Challan::findOrFail($request->get('challan_id'));

            $bilty->id = $request->get('id');
            $bilty->bilty_no = $request->get('bilty_no');
            $bilty->lg_bl_no = $request->get('lg_bl_no');
            $bilty->from = $request->get('from');
            $bilty->to = $request->get('to');
            $bilty->sender = $request->get('sender');
            $bilty->receiver = $request->get('receiver');
            $bilty->receiver_address = $request->get('receiver_address');
            $bilty->status = $request->get('status');
            $bilty->payment_status = $request->get('payment_status');
            $bilty->bilty_charges = $request->get('bilty_charges');
            $bilty->local_charges = $request->get('local_charges');

            if ($bilty->save()) {
                $bilty = Bilty::find($id);
                $bilty->customer()->associate($customer);
                $bilty->challan()->associate($challan);

                foreach (['1', '2'] as $val) {
                    $package = new Package(['id' => $val, 'package_no' => $val, 'description' => 'dfa', 'unit' => 'cbm', 'quantity' => 2, 'total_weight' => 24, 'rent' => 23, 'labour' => 32]);
                    $bilty->packages()->save($package);
                }

                BiltyResource::withoutWrapping();
                return new BiltyResource($bilty);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'error saving bilty',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
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
        $bilty = Bilty::findOrFail($id);

        if ($bilty) {
            if ($bilty->delete()) {
                BiltyResource::withoutWrapping();
                return new BiltyResource($bilty);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
        }
    }
}
