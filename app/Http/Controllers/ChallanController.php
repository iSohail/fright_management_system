<?php

namespace App\Http\Controllers;

use App\Challan;
use App\Bilty;
use Illuminate\Http\Request;
use App\Http\Resources\ChallanResource;

class ChallanController extends Controller
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
        //
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
        // return $data;
        $challan = new Challan;

        $challan->challan_no = $data['challan_no'];
        $challan->from = $data['from'];
        $challan->to = $data['to'];
        $challan->truck_no = $data['truck_no'];
        $challan->permit_no = $data['permit_no'];
        $challan->transport = $data['transport'];
        $challan->driver_name = $data['driver_name'];
        $challan->agent_name = $data['agent_name'];
        $challan->cnic = $data['cnic'];
        $challan->total_amount = $data['total_amount'];
        $challan->expenses = $data['expenses'];
        $challan->grand_total = $data['grand_total'];

        if ($challan->save()) {
            $challan = Challan::find($challan->id);
            foreach ($data['bilties'] as $id) {
                $bilty = Bilty::find($id);
                $challan->bilties()->save($bilty);
            }
            $challan->save();
            ChallanResource::withoutWrapping();
            return new ChallanResource($challan);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function last()
    {
        $challan = Challan::orderby('challan_no', 'desc')->first();
        if ($challan) {
            return $challan['challan_no'] + 1;
        }

        // default value for starting customer number
        return 1000;
    }
}
