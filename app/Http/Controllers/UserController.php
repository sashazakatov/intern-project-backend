<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->role !== 'regional_admin') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        if ($authenticatedUser->role === 'regional_admin') {
            // Проверяем, если новый пользователь принадлежит к той же стране, что и региональный админ
            if ($authenticatedUser->country !== $request->country) {
                return response()->json(['message' => 'You can only add users in your country'], 403);
            }

            // Проверяем, если новый пользователь принадлежит к тому же городу, что и региональный админ
            if ($authenticatedUser->city !== $request->city) {
                return response()->json(['message' => 'You can only add users in your city'], 403);
            }
        }

        $email = $request->only('email');
        $user = User::where('email', $email)->first();

        if ($user) {
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

        return response()->json(['user' => $user], 201);
    }
}