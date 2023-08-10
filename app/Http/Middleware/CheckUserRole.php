<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    private function checkRegionalPermissions($user, $request)
    {
        $country = $request->input('country');
        $city = $request->input('city');

    
        if ($country !== null && $city !== null) {
            if ($user->country === $country && $user->city === $city) {
                return true;
            }
        }
    
        return false;
    }
    
    public function handle(Request $request, Closure $next){
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($user->isRegionalAdmin()) {
            
            if ($this->checkRegionalPermissions($user, $request)) {
                return $next($request);
            }
            return $next($request);
        }

        return response()->json(['message' => 'Access denied'], 403);
    }
}
