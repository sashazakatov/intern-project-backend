<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
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
            'message' => 'Registration successful', 
            'user' => $user
        ], 
        201 );
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])
        ->where('password', $credentials['password'])
        ->first();

        if(!$user){
            return response()->json([ 'message' => 'Bad request' ], 400);
        }
        
        return response()->json([ 
            'message' => 'Logintion successful', 
            'user' => $user 
        ]);
    }
}
