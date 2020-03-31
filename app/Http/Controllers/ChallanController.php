<?php

namespace App\Http\Controllers;

use App\Bilty;
use App\Challan;
use App\Http\Resources\ChallanResource;
use App\Events\ChallanAdded;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $challan = Challan::with('bilties')->get();
        ChallanResource::withoutWrapping();
        return ChallanResource::collection($challan);
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

        $challan->created_at = Carbon::parse($data['date']);
        $challan->challan_no = $data['challan_no'];
        $challan->from = $data['from'];
        $challan->to = $data['to'];
        $challan->created_at = Carbon::parse($data['date']);
        $challan->truck_no = $data['truck_no'];
        $challan->permit_no = $data['permit_no'];
        $challan->transport = $data['transport'];
        $challan->driver_name = $data['driver_name'];
        $challan->agent_name = $data['agent_name'];
        $challan->cnic = $data['cnic'];
        $challan->total_amount = $data['total_amount'];
        $challan->expenses = $data['expenses'];
        $challan->grand_total = $data['grand_total'];
        $challan->user_id = Auth::user()->id;
        // return $challan;
        if ($challan->save(['timestamps' => false])) {
            $challan = Challan::find($challan->id);
            foreach ($data['bilties'] as $id) {
                $bilty = Bilty::find($id);
                $bilty->status = 'dispatched';
                $bilty->lock = 'true';
                $challan->bilties()->save($bilty);
            }
            $challan->save();
            event(new ChallanAdded());
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
        $challan = Challan::findOrFail($id);
        ChallanResource::withoutWrapping();
        return new ChallanResource($challan);
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
        $data = json_decode($request->getContent(), true);
        // return $data;
        $challan = Challan::findOrFail($id);
        
        $challan->from = $data['from'];
        $challan->to = $data['to'];
        $challan->created_at = Carbon::parse($data['date']);
        $challan->truck_no = $data['truck_no'];
        $challan->permit_no = $data['permit_no'];
        $challan->transport = $data['transport'];
        $challan->driver_name = $data['driver_name'];
        $challan->agent_name = $data['agent_name'];
        $challan->cnic = $data['cnic'];
        $challan->total_amount = $data['total_amount'];
        $challan->expenses = $data['expenses'];
        $challan->grand_total = $data['grand_total'];

        // return $challan;
        if ($challan->save(['timestamps' => false])) {
            $challan = Challan::find($challan->id);
            $bilties_challan = Bilty::where('challan_id', $challan->id)->get();
            foreach ($bilties_challan as $bilty) {
                $bilty->status = 'registered';
                $bilty->challan_id = null;
                $bilty->save();
            }
            foreach ($data['bilties'] as $id) {
                $bilty = Bilty::find($id);
                $bilty->status = 'dispatched';
                $challan->bilties()->save($bilty);
            }
            $challan->save();
            event(new ChallanAdded());
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

    public function totalCount()
    {
        $totalCount = Challan::count();
        return response()->json([
            'status' => 'success',
            'total_challans' => $totalCount,
        ], 200);
    }
}
