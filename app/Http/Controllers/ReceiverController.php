<?php

namespace App\Http\Controllers;
use App\Receiver;
use App\Http\Resources\ReceiverResource;

use Illuminate\Http\Request;

class ReceiverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receivers = Receiver::with('customer')->get();
        ReceiverResource::withoutWrapping();
        return ReceiverResource::collection($receivers);
    }

    public function paginate()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $receivers = Receiver::with('customer')->latest()->paginate($per_page);
        return ReceiverResource::collection($receivers);
    }

    public function search()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $receivers = Receiver::search(request()->query('query'))->paginate($per_page);
        return ReceiverResource::collection($receivers);
    }

    public function sort()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $sort_desc = request()->query('sort_desc');
        $sort_by = request()->query('sort_by');
        if ($sort_desc == 'true') {
            $sort_desc = 'DESC';
        } else {
            $sort_desc = 'ASC';
        }
        $receivers = Receiver::with('customer')->orderBy($sort_by, $sort_desc)->paginate($per_page);
        return ReceiverResource::collection($receivers);
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

        $receiver = new Receiver;
        $receiver->name = $data['name'];
        $receiver->address = $data['address'];
        $receiver->customer_id = $data['customer_id'];
        $receiver->save();
        ReceiverResource::withoutWrapping();
        return new ReceiverResource($receiver);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $receiver = Receiver::findOrFail($id);
        ReceiverResource::withoutWrapping();
        return new ReceiverResource($receiver);
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
        $receiver = Receiver::findOrFail($id);
        $receiver->delete();
    }
}
