<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sender;
use App\Http\Resources\SenderResource;

class SenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $senders = Sender::with('customer')->get();
        SenderResource::withoutWrapping();
        return SenderResource::collection($senders);
    }
    
    public function paginate()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $senders = Sender::with('customer')->latest()->paginate($per_page);
        return SenderResource::collection($senders);
    }

    public function search()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $senders = Sender::search(request()->query('query'))->paginate($per_page);
        return SenderResource::collection($senders);
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
        $senders = Sender::with('customer')->orderBy($sort_by, $sort_desc)->paginate($per_page);
        return SenderResource::collection($senders);
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

        $sender = new Sender;
        $sender->name = $data['name'];
        $sender->address = $data['address'];
        $sender->customer_id = $data['customer_id'];
        $sender->save();
        SenderResource::withoutWrapping();
        return new SenderResource($sender);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sender = Sender::findOrFail($id);
        SenderResource::withoutWrapping();
        return new SenderResource($sender);
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
        $sender = Sender::findOrFail($id);
        $sender->delete();
    }
}
