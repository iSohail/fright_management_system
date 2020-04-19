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
    
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        UserResource::withoutWrapping();
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        // return $request;
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

        // return $request;

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

        // return $request;
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

        // return $request;

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
