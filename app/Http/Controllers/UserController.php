<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{

    public function add(UserCreateRequest $request)
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

        return response()->json(['user' => $user], 201);
    }

    public function delete(Request $request){

        $user = User::find($request->id);

        if(!$user){
            return response()->json(['message' => 'User is not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUserInfo(UserUpdateRequest $request)
    {
        $currentUser = Auth::user();

        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'User is not found'], 404);
        }

        if ($currentUser->isAdmin()) {
            $data = $request->only(['name', 'surname', 'email', 'role', 'password', 'country', 'city', 'address', 'phone_number']);
            $user->update($data);

            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }
        if ($currentUser->isRegionalAdmin() && $currentUser->city === $user->city && $currentUser->country === $user->country) {

            $data = $request->only(['name', 'password', 'address', 'phone_number', 'avatar']);
            $user->update($data);

            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }
        if (($currentUser->isDeviceOwner() || $currentUser->isCustomer()) && $currentUser->id === $user->id) {
            $data = $request->only(['password', 'address', 'phone_number', 'avatar']);
            $user->update($data);
            
            return response()->json(["message" => "Data updated successfully", "user" => $user]);
        }

        return response()->json(['message' => 'You do not have access to edit the user'], 403);
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

    public function checkEmail(Request $request){
        $email = $request->email;
        if(!$email){
            return response()->json(['message' => 'bad request'], 400);
        }

        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json(['isExists' => false]);
        }

        return response()->json([ 'isExists' => true ]);
    }

    public function getUser()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $users = User::where('id', '!=', $user->id)->get();

            return response()->json(['users' => $users], 200);
        }
        if ($user->isRegionalAdmin()) {
            $users = User::where('id', '!=', $user->id)
            ->where('country', $user->country)
            ->where('city', $user->city)
            ->get();

            return response()->json(['users' => $users], 200);
        }

        return response()->json(['message' => 'Access denied'], 403);
    }
}