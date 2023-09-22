<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\LoginRequest;
use Edujugon\PushNotification\Facades\PushNotification;

class AuthController extends Controller
{
    public function register(UserCreateRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if($request->role === 'owner'){
            $regionalAdmin = User::where('id', $request->administrator_id)
            ->where('role', 'regional_admin')
            ->where('city', $request->city)
            ->where('country', $request->country)->first();

            if (!$regionalAdmin) {
                return response()->json(['message' => 'bad request'], 422);
            }
        }

        $user = User::create($request->all());

        return response()->json( [ 'message' => 'Registration successful' ], 201 );
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {            
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;
        $refreshToken = $user->createToken('authToken', [''])->accessToken;
            
        if($request->devices_token) {
        	$push = PushNotification::setService('fcm')
    		->setMessage([
        		'notification' => [
            		'title'=>'APP',
            		'body'=>'Successful login from',
            		'sound' => 'default'
        		]
    		])
    		->setDevicesToken($request->devices_token)
    		->send()->getFeedback();
                
        	return response()->json(["ffff" => $push]);
    	}

        return response()->json([ 
            'message' => 'Login successful', 
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ]);
    }
}
