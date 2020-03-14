<?php

namespace App\Http\Controllers;

use App\Bilty;
use App\Challan;
use App\Customer;
use App\Package;
use App\Http\Resources\BiltyResource;
use Illuminate\Support\Facades\Validator;
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
        $this->middleware('isAdmin')->only('destroy');
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

        $customer = NULL;

        $bilty = new Bilty;
        
        if (isset($data['customer_id'])){
            if (gettype($data['customer_id']) == "string"){
                $customer = Customer::findOrFail($data['customer_id']);
            }
        }
        
        $bilty->bilty_no = $data['bilty_no'];
        $bilty->lg_bl_no = $data['lc_bl_no'];
        $bilty->from = $data['from'];
        $bilty->to = $data['to'];
        $bilty->sender = $data['sender'];
        $bilty->receiver = $data['receiver'];
        $bilty->receiver_address = $data['receiver_address'];
        $bilty->status = $data['status_item_value'];
        $bilty->payment_status = $data['payment_status_item_value'];
        $bilty->bilty_charges = $data['bilty_charges'];
        $bilty->local_charges = $data['local_charges'];
        $bilty->bilty_total = $data['bilty_total'];
        $bilty->packages_total = $data['packages_total'];
        $bilty->manual = $data['manual'];

        if ($bilty->save()) {
            $bilty = Bilty::find($bilty->id);
            if($customer){
                $bilty->customer()->associate($customer);
            } 

            foreach ($data['packages'] as $val) {
                $package = Package::orderby('package_no', 'desc')->first();
                $package_no = 1000;
                if ($package) {
                    $package_no = $package['package_no'] + 1;
                }
                $package = new Package(['package_no' => $package_no, 'description' => $val['description'], 'total_volume' => $val['total_volume'], 'rate' => $val['rate'], 'unit' => $val['unit'], 'quantity' => $val['quantity'], 'total_weight' => $val['total_weight'], 'rent' => $val['rent'], 'labour' => $val['labour']]);
                $bilty->packages()->save($package);
            }
            $bilty->save();
            
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
    {// converting JSON object into PHP code
        $data = json_decode($request->getContent(), true);

        // return $data;
        $bilty = Bilty::findOrFail($id);
        $customer = -1;
        if ($data['customer_id'] != -1){
            $customer = Customer::findOrFail($data['customer_id']);
        }
        // $challan = Challan::findOrFail($data['challan_id']);
        // return $customer;
        $bilty->lg_bl_no = $data['lc_bl_no'];
        $bilty->from = $data['from'];
        $bilty->to = $data['to'];
        $bilty->sender = $data['sender'];
        $bilty->receiver = $data['receiver'];
        $bilty->receiver_address = $data['receiver_address'];
        $bilty->status = $data['status_item_value'];
        $bilty->payment_status = $data['payment_status_item_value'];
        $bilty->bilty_charges = $data['bilty_charges'];
        $bilty->local_charges = $data['local_charges'];

        // $packages=['1','2','3'];
        // return $bilty;
        if ($bilty->save()) {
            

            $bilty = Bilty::find($bilty->id);
            // return $bilty;
            if($customer != -1){
                $bilty->customer()->associate($customer);
            } else{
                // return 'dafad';
                // return $bilty->id;
                $bilty->customer_id = null;
                // $bilty->save();
            }
            // $bilty->challan()->associate($challan);
            $packages_bilty = Package::where('bilty_id', $bilty->id)->get();
            foreach ($packages_bilty as $package) {
                $package->delete();
            }
            
            foreach ($data['packages'] as $val) {
                $package = Package::orderby('package_no', 'desc')->first();
                $package_no = 1000;
                if ($package) {
                    $package_no = $package['package_no'] + 1;
                }
                $package = new Package(['package_no' => $package_no, 'description' => $val['description'], 'total_volume' => $val['total_volume'], 'rate' => $val['rate'], 'unit' => $val['unit'], 'quantity' => $val['quantity'], 'total_weight' => $val['total_weight'], 'rent' => $val['rent'], 'labour' => $val['labour']]);
                $bilty->packages()->save($package);
            }
            $bilty->save();
            // return $bilty->id;

            // $bilty = Bilty::find($bilty->id);
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

    public function last()
    {
        $bilty = Bilty::orderby('bilty_no', 'desc')->first();
        if ($bilty) {
            return $bilty['bilty_no'] + 1;
        }

        // default value for starting customer number
        return 1000;
    }
}
