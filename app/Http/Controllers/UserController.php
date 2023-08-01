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

            if ($authenticatedUser->country !== $user->country) {
                return response()->json(['message' => 'You can only add users in your country'], 403);
            }

            if ($authenticatedUser->city !== $user->city) {
                return response()->json(['message' => 'You can only add users in your city'], 403);
            }
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUserInfo(Request $request)
    {
        $currentUser = Auth::user();
        $user = User::find($request->id);
        $isUsedEmail = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User is not found'], 404);
        }
        if($isUsedEmail){
            return response()->json([
                'message' => 'This email is already in use',
            ]);
        }

        if ($currentUser->isAdmin()) {
            $data = $request->only(['name', 'surname', 'email', 'role', 'password', 'country', 'city', 'address', 'phone_number']);
            $user->update($data);

            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }
        if ($currentUser->isRegionalAdmin() && $currentUser->city === $user->city && $currentUser->country === $user->coutry) {
            $data = $request->only(['name', 'password', 'address', 'phone', 'avatar']);
            $user->update($data);

            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }
        if (($currentUser->isDeviceOwner() || $currentUser->isCustomer()) && $currentUser->id === $user->id) {
            $data = $request->only(['password', 'address', 'phone', 'avatar']);
            $user->update($data);
            
            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }

        return response()->json(['message' => 'You do not have permission to edit this user'], 403);
    }

    public function getUserInfo(Request $request)
    {
        $authenticatedUser = Auth::user();

        return response()->json(['user' => $authenticatedUser]);
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $filePath = $file->store('avatars', 'public');

            $avatarUrl = asset('storage/' . $filePath);

            $user->avatar = $avatarUrl;
            $user->save();

            return response()->json(['message' => 'Avatar uploaded successfully']);
        }
        
        return response()->json(['message' => 'File not found'], 400);
    }
}