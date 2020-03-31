<?php

namespace App\Http\Controllers;

use App\Bilty;
use App\Customer;
use App\Events\LedgerAdded;
use App\GeneralLedger;
use App\Http\Resources\GeneralLedgerResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledgers = GeneralLedger::with('bilties')->get();
        GeneralLedgerResource::withoutWrapping();
        return GeneralLedgerResource::collection($ledgers);
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
        // return $data['bilties'];
        $ledger = new GeneralLedger;

        $customer = null;
        if (isset($data['customer_id'])) {
            if (gettype($data['customer_id']) == "string") {
                $customer = Customer::findOrFail($data['customer_id']);
            }
        }
        $ledger->ledger_no = $data['ledger_no'];
        $ledger->description = $data['description'];
        $ledger->income_tax = $data['income_tax'];
        $ledger->sales_tax = $data['sales_tax'];
        $ledger->bilty_total = $data['bilty_total'];
        $ledger->account_receivable = $data['account_receivable'];
        $ledger->amount_paid = $data['amount_paid'];
        $ledger->pending_amount = $data['pending_amount'];
        $ledger->status = $data['status'];
        $ledger->user_id = Auth::user()->id;

        if ($ledger->save()) {
            $ledger = GeneralLedger::findOrFail($ledger->id);
            if ($customer) {
                $ledger->customer()->associate($customer);
                $ledger->save();
            }
            $bilties_arr = [];
            foreach ($data['bilties'] as $val) {
                $bilty = Bilty::find($val);
                $bilty->ledger_id = $ledger->id;
                $bilty->lock = 'true';
                $bilty->save();
            }
            event(new LedgerAdded());
            GeneralLedgerResource::withoutWrapping();
            return new GeneralLedgerResource($ledger);
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
        $ledger = GeneralLedger::findOrFail($id);
        GeneralLedgerResource::withoutWrapping();
        return new GeneralLedgerResource($ledger);
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
        // return $data['bilties'];
        $ledger = GeneralLedger::findOrFail($id);

        $customer = null;
        if (isset($data['customer_id'])) {
            if (gettype($data['customer_id']) == "string") {
                $customer = Customer::findOrFail($data['customer_id']);
            }
        }
        $ledger->description = $data['description'];
        $ledger->income_tax = $data['income_tax'];
        $ledger->sales_tax = $data['sales_tax'];
        $ledger->bilty_total = $data['bilty_total'];
        $ledger->account_receivable = $data['account_receivable'];
        $ledger->amount_paid = $data['amount_paid'];
        $ledger->pending_amount = $data['pending_amount'];
        $ledger->status = $data['status'];

        // return $ledger;
        if ($ledger->save()) {
            $ledger = GeneralLedger::findOrFail($ledger->id);

            if (isset($customer)) {
                $ledger->customer()->associate($customer);
            } else {
                $ledger->customer_id = null;
            }
            $ledger->save();
            $bilties = Bilty::where('ledger_id', $ledger->id)->get();
            foreach ($bilties as $bilty) {
                $bilty->ledger_id = null;
                $bilty->save();
            }
            $bilties_arr = [];
            foreach ($data['bilties'] as $val) {
                $bilty = Bilty::find($val);
                $bilty->ledger_id = $ledger->id;
                $bilty->lock = 'true';
                $bilty->save();
            }
            event(new LedgerAdded());
            GeneralLedgerResource::withoutWrapping();
            return new GeneralLedgerResource($ledger);
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
        $general_ledger = GeneralLedger::orderby('ledger_no', 'desc')->first();
        if ($general_ledger) {
            return $general_ledger['ledger_no'] + 1;
        }

        // default value for starting customer number
        return 1000;
    }

    public function changePaymentStatus(Request $request, $status)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $id) {
            $ledger = GeneralLedger::findOrFail($id);
            if ($status == 'cleared') {
                $ledger->amount_paid = $ledger->account_receivable;
                $ledger->pending_amount = 0;
                $ledger->paid_on = Carbon::now();
            }
            $ledger->status = $status;
            $ledger->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'status changed to ' . $status,
        ], 200);
    }

    public function showCustomerLedgers($customer_id)
    {
        // return $customer_id;
        $ledgers = Customer::find($customer_id)->generalLedgers;
        // return $ledgers;
        GeneralLedgerResource::withoutWrapping();
        return GeneralLedgerResource::collection($ledgers);
    }

    public function showCustomerAccounts($customer_id)
    {
        $ledgers = Customer::find($customer_id)->generalLedgers;
        $amount_paid = 0;
        $amount_pending = 0;
        if ($ledgers) {
            foreach ($ledgers as $ledger) {
                $amount_paid += $ledger['amount_paid'];
                $amount_pending += $ledger['pending_amount'];
            }
        }
        return response()->json([
            'status' => 'success',
            'amount_paid' => $amount_paid,
            'amount_pending' => $amount_pending,
        ], 200);
    }

    public function showPaidAmount()
    {
        $ledgers = GeneralLedger::where('status', "cleared")->get();
        $amount_paid = 0;
        if ($ledgers) {
            foreach ($ledgers as $ledger) {
                $amount_paid += $ledger['amount_paid'];
            }
        }
        return response()->json([
            'status' => 'success',
            'amount_paid' => $amount_paid,
        ], 200);
    }

    public function showPendingLedgers()
    {
        // return '234';
        $ledgers = GeneralLedger::where('status', "pending")->get();
        GeneralLedgerResource::withoutWrapping();
        return GeneralLedgerResource::collection($ledgers);
    }

}
