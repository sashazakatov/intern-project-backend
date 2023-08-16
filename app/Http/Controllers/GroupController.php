<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Http\Requests\UserIdRequest;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    public function create(GroupRequest $request){
        $currentUser = Auth::user();

        $request->merge([ 'administrator_id' => $currentUser->id ]);

        $group = Group::create($request->all());

        return response()->json([
            'message' => 'Group added successfully', 
            'group' => $group],
            201
        );
    }
    public function get(){
        $currentUser = Auth::user();
        $groups = Group::where('administrator_id', $currentUser->id)->get();

        return response()->json(["groups" => $groups]);
    }
    public function delete(UserIdRequest $request){
        $currentUser = Auth::user();
        $groups = Group::where('id', $request->id)
        ->first();
        
        if(!$groups){
            return response()->json(['message' => 'group not found'], 404);
        }
        if($groups->administrator_id === $currentUser->id){
            return response()->json(['message' => 'you do not have access to it']);
        }

        $groups->delete();

        return response()->json(['message' => 'group deleted successfully']);
    }
    public function edit(UserIdRequest $request){
        $currentUser = Auth::user();
        $groups = Group::where('id', $request->id)
        ->first();

        $data = $request->only([ 'name' ]);

        if(!$groups){
            return response()->json(['message' => 'group not found'], 404);
        }
        if($groups->administrator_id !== $currentUser->id){
            return response()->json(['message' => 'you do not have access to it']);
        }

        $groups->update($data);

        return response()->json(["message" => "group updated successfully", "user" => $groups]);
    }
}