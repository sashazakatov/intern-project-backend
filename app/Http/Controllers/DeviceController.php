<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Device;

use App\Http\Requests\DeviceAddRequest;


class DeviceController extends Controller
{
    public function create(DeviceAddRequest $request)
    {
        $currentUser = Auth::user();
        $owner = User::find($request->owner_id);
    
        if (!$owner->isOwner()) {
            return response()->json(['message' => 'You are not the owner'], 403);
        }
    
        if ($currentUser->isRegionalAdmin()) {
            $regionalAdmin = User::find($currentUser->id);
            if($currentUser->id !== $regionalAdmin->id){
                return response()->json([ 'message' => 'fff' ]);
            }
            if ($currentUser->city !== $owner->city || $currentUser->country !== $owner->country) {
                return response()->json(['message' => 'You do not have permission to create a device for this owner'], 403);
            }
        } elseif ($currentUser->isAdmin()) {
            $regionalAdmin = User::find($currentUser->id);
            if ($regionalAdmin->city !== $owner->city || $regionalAdmin->country !== $owner->country) {
                return response()->json(['message' => 'You do not have permission to create a device for this owner'], 403);
            }
        }
    
        $device = Device::create($request->all());
    
        return response()->json(['message' => 'Device added successfully', 'device' => $device], 201);
    }
    public function edit(){

        return response()->json([ 'message' => 'it is edit device' ]);
    }
    public function delete(Request $request){
        $currentUser = Auth::user();
        $device = Device::find($request->id);

        if(!$device){
            return response()->json(['message' => 'Device is not found'], 404);
        }

        if ($currentUser->isRegionalAdmin()) {
            if ($currentUser->id !== $device->administrator_id) {
                return response()->json(['message' => 'bad request'], 400);
            }
        }

        $device->delete();

        return response()->json(['message' => 'Device deleted successfully']);
    }
}
