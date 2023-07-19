<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUser ($id){
        $user = User::find($id);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function add (Request $request) {

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);


        return response()->json([
            'user' => $user,
        ]);
    }
}
