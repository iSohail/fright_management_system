<?php

namespace App\Http\Controllers;

use App\Bilty;
use App\Customer;
use App\Challan;
use App\Events\BiltyAdded;
use App\Http\Resources\BiltyResource;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        $customer = null;
        $bilty = new Bilty;
        if (isset($data['customer_id'])) {
            if (gettype($data['customer_id']) == "string") {
                $customer = Customer::findOrFail($data['customer_id']);
            }
        }
        $validatedData = $request->validate([
            'date' => 'required',
            'bilty_no' => 'required',
            
            'from' => 'required',
            'to' => 'required',
            'sender' => 'required',
            'receiver' => 'required',
            'receiver_address' => 'required',
            'payment_status_item_value' => 'required',
            'bilty_charges' => 'required',
            'local_charges' => 'required',
            'bilty_total' => 'required',
            'packages_total' => 'required',
            'manual' => 'required',
        ]);
        $bilty->created_at = Carbon::parse($data['date']);
        $bilty->bilty_no = $data['bilty_no'];
        if (!empty($data['lc_bl_no'])) {
            $bilty->lg_bl_no = $data['lc_bl_no'];
        }
        if (!empty($data['description'])) {

            $bilty->description = $data['description'];
        }
        $bilty->from = $data['from'];
        $bilty->to = $data['to'];
        $bilty->sender = $data['sender'];
        $bilty->receiver = $data['receiver'];
        $bilty->receiver_address = $data['receiver_address'];
        $bilty->status = 'registered';
        $bilty->payment_status = $data['payment_status_item_value'];
        $bilty->bilty_charges = $data['bilty_charges'];
        $bilty->local_charges = $data['local_charges'];
        $bilty->bilty_total = $data['bilty_total'];
        $bilty->packages_total = $data['packages_total'];
        $bilty->manual = $data['manual'];
        $bilty->user_id = Auth::user()->id;

        // return $bilty;
        if ($bilty->save(['timestamps' => false])) {
            $bilty = Bilty::find($bilty->id);
            if ($customer) {
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

            event(new BiltyAdded());
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
    { // converting JSON object into PHP code
        $data = json_decode($request->getContent(), true);

        // return $data;
        $bilty = Bilty::findOrFail($id);
        // $customer = -1;
        if ($data['customer_id'] != -1) {
            $customer = Customer::findOrFail($data['customer_id']);
        }
        // return $bilty;
        $validatedData = $request->validate([
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
            'sender' => 'required',
            'receiver' => 'required',
            'receiver_address' => 'required',
            'payment_status_item_value' => 'required',
            'bilty_charges' => 'required',
            'local_charges' => 'required',
            'bilty_total' => 'required',
            'packages_total' => 'required',
            'manual' => 'required',
        ]);
        // $challan = Challan::findOrFail($data['challan_id']);
        // return $customer;
        $bilty->created_at = Carbon::parse($data['date']);

        if (!empty($data['lc_bl_no'])) {
            $bilty->lg_bl_no = $data['lc_bl_no'];
        }
        if (!empty($data['description'])) {

            $bilty->description = $data['description'];
        }
        $bilty->from = $data['from'];
        $bilty->to = $data['to'];
        $bilty->sender = $data['sender'];
        $bilty->receiver = $data['receiver'];
        $bilty->receiver_address = $data['receiver_address'];
        $bilty->payment_status = $data['payment_status_item_value'];
        $bilty->bilty_charges = $data['bilty_charges'];
        $bilty->local_charges = $data['local_charges'];
        $bilty->bilty_total = $data['bilty_total'];
        $bilty->packages_total = $data['packages_total'];
        // return $bilty;

        // $packages=['1','2','3'];
        if ($bilty->save(['timestamps' => false])) {

            $bilty = Bilty::find($bilty->id);
            if (isset($customer)) {
                // return $bilty;
                $bilty->customer()->associate($customer);
            } else {
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
            event(new BiltyAdded());
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

    public function changeStatus(Request $request, $status)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $id) {
            $bilty = Bilty::findOrFail($id);
            $bilty->status = $status;
            $bilty->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'status changed to ' . $status,
        ], 200);
    }

    public function changePaymentStatus(Request $request, $status)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $id) {
            $bilty = Bilty::findOrFail($id);
            $bilty->payment_status = $status;
            $bilty->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'status changed to ' . $status,
        ], 200);
    }

    public function totalCount()
    {
        $totalCount = Bilty::count();
        return response()->json([
            'status' => 'success',
            'total_bilties' => $totalCount,
        ], 200);
    }

    public function showCustomerBilties($customer_id)
    {
        $bilties = Customer::find($customer_id)->bilties;
        $monthly_bilties = [];
        foreach ($bilties as $bilty) {
            if(isset($bilty['ledger_id'])){
                continue;
            }
            if ($bilty['payment_status'] != 'monthly') {
                continue;
            }
            array_push($monthly_bilties, $bilty);
        }
        BiltyResource::withoutWrapping();
        return BiltyResource::collection($monthly_bilties);
    }

    public function showChallanBilties($challan_id)
    {
        $bilties = Challan::find($challan_id)->bilties;
        BiltyResource::withoutWrapping();
        return BiltyResource::collection($bilties);
    }

    public function monthlyTotal()
    {
        $now = Carbon::now();
        $bilties = DB::table('bilties')
            ->whereYear('created_at', $now->year)
            ->pluck('created_at');

        return $bilties;
    }

    public function showRegisteredBilties()
    {
        $bilties = Bilty::where('status', 'registered')->get();

        BiltyResource::withoutWrapping();
        return BiltyResource::collection($bilties);
    }

    public function resetRegisteredBilties(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // return $data;
        foreach ($data as $data_bilty) {
            // return $data_bilty['id'];
            $bilty = Bilty::findOrFail($data_bilty['id']);
            $bilty->status = 'registered';
            $bilty->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'bilty registeration reset',
        ], 200);

        // $bilties = Bilty::where('status', 'registered')->get();

        // BiltyResource::withoutWrapping();
        // return BiltyResource::collection($bilties);
    }
}
