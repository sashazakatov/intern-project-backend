<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Group;

class CheckGroupAccess
{
    public function handle(Request $request, Closure $next)
    {
        $currentUser = Auth::user();
        $group = Group::where('id', $request->id)->first();

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if ($group->administrator_id !== $currentUser->id) {
            return response()->json(['message' => 'You do not have access to this group'], 403);
        }

        return $next($request);
    }
}
