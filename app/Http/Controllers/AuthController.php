<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'Already exists'], 401);
        }

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

        if (!Auth::attempt($credentials)) {            
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;
        $refreshToken = $user->createToken('authToken', [''])->accessToken;

        return response()->json([ 
            'message' => 'Login successful', 
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ]);
    }
}
