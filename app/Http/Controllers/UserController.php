<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('_role')->get();
        return UserResource::collection($users);
    }

    public function paginate()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $users = User::with('_role')->latest()->paginate($per_page);
        return UserResource::collection($users);
    }

    public function search()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');
        $users = User::search(request()->query('query'))->paginate($per_page);
        return UserResource::collection($users);
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
        $users = User::with('_role')->orderBy($sort_by, $sort_desc)->paginate($per_page);
        return UserResource::collection($users);
    }
    
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        UserResource::withoutWrapping();
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $validatedData = $request->validate([
            'name' => 'required',
            'user_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($request['role'] == '2') {
            return response()->json([
                'status' => 'error',
                'message' => 'can not assign admin role',
            ], 500);
        }

        $user = new User;
        $user->name = $request['name'];
        $user->user_name = $request['user_name'];
        $user->email = $request['email'];
        $user->password =  Hash::make($request['password']);
        $user->role = $request['role'];

        if ($user->save()) {
            UserResource::withoutWrapping();
            return new UserResource($user);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'can not add user',
        ], 500);

    }

    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'user_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($request['role'] == '2') {
            return response()->json([
                'status' => 'error',
                'message' => 'can not assign admin role',
            ], 500);
        }

        $user->name = $request['name'];
        $user->user_name = $request['user_name'];
        $user->email = $request['email'];
        $user->password =  Hash::make($request['password']);
        $user->role = $request['role'];

        if ($user->save()) {
            UserResource::withoutWrapping();
            return new UserResource($user);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'can not update user',
        ], 500);

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $bilties = $user->bilties;
        $challans = $user->challans;
        $ledgers = $user->generalLedgers;
        if ($user) {
            if ($user->delete()) {
                foreach ($bilties as $bilty) {
                    $bilty->user_id = NULL;
                }
                foreach ($challans as $challan) {
                    $challan->user_id = NULL;
                }
                foreach ($ledgers as $ledger) {
                    $ledger->user_id = NULL;
                }
                UserResource::withoutWrapping();
                return new UserResource($user);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
        }
    }

    public function changePassword(Request $request, $id) {
        $user = User::findOrFail($id);
        if ($user) {
            $user->password = Hash::make($request['password']);
            $user->save();
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'resource not found',
            ], 404);
        }
    }
}
