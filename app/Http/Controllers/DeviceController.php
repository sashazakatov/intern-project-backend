<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Device;

use App\Http\Requests\UserIdRequest;
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
            if ($currentUser->city !== $owner->city || $currentUser->country !== $owner->country) {
                return response()->json(['message' => 'You do not have permission to create a device for this owner'], 403);
            }

            $request->merge([ 'administrator_id' => $currentUser->id ]);

        } elseif ($currentUser->isAdmin()) {
            $regionalAdmin = User::where('id', $request->administrator_id)
            ->where('role', 'regional_admin')->first();
        
            if(!$regionalAdmin){
                return response()->json(['message' => 'bad request'], 400);
            }
            if ($regionalAdmin->city !== $owner->city || $regionalAdmin->country !== $owner->country) {
                return response()->json(['message' => 'You do not have permission to create a device for this owner'], 403);
            }
        }
        
        $request->merge([ 'city' => $currentUser->city, 'country' => $currentUser->country]);
        $device = Device::create($request->all());
    
        return response()->json(['message' => 'Device added successfully', 'device' => $device], 201);
    }
    public function edit(Request $request){
        $currentUser = Auth::user();

        $device = Device::find($request->id);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $data = $request->only([
            'serial_number', 'owner_id', 'administrator_id', 'name', 'device_type', 'phase_active',
            'phase_type', 'sum_power', 'group_id', 'location', 'country', 'city','address'
        ]);

        if ($currentUser->id === $device->administrator_id && $currentUser->isRegionalAdmin()) {

            $device->update($data);

            return response()->json(["message" => "Data updated successfully", "device" => $device]);
        }
        else if($currentUser->isAdmin()){
            return response()->json(["message" => "Data updated successfully", "device" => $device]);
        }

        return response()->json(['message' => 'You do not have access to edit the device'], 403);
    }
    public function delete(UserIdRequest $request){
        $currentUser = Auth::user();
        $device = Device::find($request->id);

        if(!$device){
            return response()->json(['message' => 'Device is not found'], 404);
        }

        if ($currentUser->isRegionalAdmin()) {
            if ($currentUser->id !== $device->administrator_id) {
                return response()->json(['message' => 'bad request'], 400);
            }
            if($currentUser->city !== $device->city || $currentUser->country !== $device->country){
                return response()->json(['message' => 'bad request'], 400);
            }
        }

        $device->delete();

        return response()->json(['message' => 'Device deleted successfully']);
    }

    public function getDevices(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $devices = Device::all();
        } elseif ($user->isRegionalAdmin()) {
            $devices = Device::where('administrator_id', $user->id)->get();
        } elseif ($user->isOwner()) {
            $devices = Device::where('owner_id', $user->id)->get();
        } elseif($user->isCustomer()) {
            abort(403, 'Permission denied');
        }
        
        return response()->json([ 'devices' => $devices ]);
    }
}
