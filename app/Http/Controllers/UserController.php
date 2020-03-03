<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

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
}
