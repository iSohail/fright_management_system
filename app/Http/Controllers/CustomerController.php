<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\CustomerAdded;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function paginate()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $customers = Customer::with('bilties')->latest()->paginate($per_page);
        return CustomerResource::collection($customers);
    }
    public function search()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $customers = Customer::search(request()->query('query'))->paginate($per_page);
        return CustomerResource::collection($customers);
    }
    public function sort()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $sort_desc = request()->query('sort_desc');
        $sort_by = request()->query('sort_by');
        // return $sort_by;
        if ($sort_by == 'no') {
            $sort_by = 'customer_no';
        }
        if ($sort_by == 'cellNo') {
            $sort_by = 'cell_no';
        }
        if ($sort_desc == 'true') {
            $sort_desc = 'DESC';
        } else {
            $sort_desc = 'ASC';
        }
        // return $sort_desc;
        $customers = Customer::with('bilties')->orderBy($sort_by, $sort_desc)->paginate($per_page);
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

        $customer_no_stored = Customer::where('customer_no', $data['customerNo'])->get();
        if (count($customer_no_stored) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'customer no already used',
            ], 500);
        }

        $customer = new Customer;

        if (isset($data['email'])) {
            $customer->email = $data['email'];
        }
        if (isset($data['cellNo'])) {
            $customer->cell_no = $data['cellNo'];
        }
        if (isset($data['perKg'])) {
            $customer->per_kg_rate = $data['perKg'];
        }
        if (isset($data['perCbm'])) {
            $customer->per_cbm_rate = $data['perCbm'];
        }
        if (isset($data['perPckg'])) {
            $customer->per_pck_rate = $data['perPckg'];
        }
        if (isset($data['incomeTax'])) {
            $customer->income_tax = $data['incomeTax'];
        }
        if (isset($data['salesTax'])) {
            $customer->sales_tax = $data['salesTax'];
        }
        $customer->name = $data['customerName'];
        $customer->customer_no = $data['customerNo'];
        $customer->company = $data['company'];

        // return $customer->save();

        if ($customer->save()) {
            $customer = Customer::findOrFail($customer->id);
            // $sender = new Sender;
            // $sender->name = $data['sender'];
            // $sender->address = $data['sender_address'];
            // $sender->customer()->associate($customer);

            // $sender->save();

            // $receiver = new Receiver;
            // $receiver->name = $data['receiver'];
            // $receiver->address = $data['receiver_address'];
            // $receiver->customer()->associate($customer);

            // $receiver->save();
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
        if (isset($data['email'])) {
            $customer->email = $data['email'];
        }
        if (isset($data['cellNo'])) {
            $customer->cell_no = $data['cellNo'];
        }
        if (isset($data['perKg'])) {
            $customer->per_kg_rate = $data['perKg'];
        }
        if (isset($data['perCbm'])) {
            $customer->per_cbm_rate = $data['perCbm'];
        }
        if (isset($data['perPckg'])) {
            $customer->per_pck_rate = $data['perPckg'];
        }
        if (isset($data['incomeTax'])) {
            $customer->income_tax = $data['incomeTax'];
        }
        if (isset($data['salesTax'])) {
            $customer->sales_tax = $data['salesTax'];
        }
        $customer->name = $data['name'];
        $customer->company = $data['company'];

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

    public function totalCount()
    {
        $totalCount = Customer::count();
        return response()->json([
            'status' => 'success',
            'total_customers' => $totalCount,
        ], 200);
    }

    public function topCustomer()
    {
        // $customers = DB::table('general_ledgers')->where('status', 'cleared')->orderBy('amount_paid', 'desc')->pluck('customer_id');
        $customers = DB::table('customers')
            ->join('general_ledgers', 'customers.id', '=', 'general_ledgers.customer_id')
            ->where('general_ledgers.status', 'cleared')
            ->select('customers.id', 'customers.customer_no', 'customers.name', 'general_ledgers.amount_paid')
            ->get();

        // return $customers;

        return $customers;
        $customers_data = [];
        $customers_id = [];
        $counter = 0;
        foreach ($customers as $k => $v) {
            $arr = (array) $v;
            // return $arr['id'];
            // return $v[$k];
            // foreach ($v as $k2 => $v2) {
            //     // if ($k2 == 'id'){
            //     //     if(!($customers_id[$v2]) ){
            //     //         array_push($customer_id, $v2);
            //     //     }
            //     // }
            //     if (isset($customers_data[$v2]))
            //     {
            //         $b[$key] += $value;
            //     }
            //     else
            //     {
            //         $b[$key] = $value;
            //     }
            //     // dd(gettype ( $v ));
            //         // if($k2 == 'id'){

            //         // }
            // }
        }
        foreach ($customers as $k => $v) {
            array_search("red", $a);
        }
        // foreach ($customers as $id) {
        //     if($counter < 10){
        //         if($id){
        //             $customer = Customer::findOrFail($id);
        //             array_push($customers_data, $customer);
        //         }
        //         $counter++;
        //     } else{
        //         break;
        //     }
        // }
        return $customers_id;
        CustomerResource::withoutWrapping();
        return CustomerResource::collection($customers_data);
    }
}
