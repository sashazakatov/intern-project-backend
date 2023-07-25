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

    public function delete(Request $request){
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->role !== 'regional_admin') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        $user = User::find($request->id);

        if(!$user){
            return response()->json(['message' => 'User is not found'], 404);
        }

        if ($authenticatedUser->role === 'regional_admin') {
            // Проверяем, если новый пользователь принадлежит к той же стране, что и региональный админ
            if ($authenticatedUser->country !== $user->country) {
                return response()->json(['message' => 'You can only add users in your country'], 403);
            }

            // Проверяем, если новый пользователь принадлежит к тому же городу, что и региональный админ
            if ($authenticatedUser->city !== $user->city) {
                return response()->json(['message' => 'You can only add users in your city'], 403);
            }
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUserInfo(Request $request){
        $authenticatedUser = Auth::user();

        // Проверяем, что пользователь редактирует свой профиль
        if ($authenticatedUser->id !== $request->user()->id) {
            return response()->json(['message' => 'You do not have permission to edit this profile'], 403);
        }

        // Обновляем данные пользователя
        $authenticatedUser->update($request->all());

        return response()->json([
            'message' => 'Data updated successfully',
            'user' => $authenticatedUser,
        ]);
    }

    public function getUserInfo(Request $request)
    {
        $authenticatedUser = Auth::user();

        // dd($authenticatedUser);

        return response()->json(['user' => $authenticatedUser]);
    }
}