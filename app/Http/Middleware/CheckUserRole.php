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
    public function handle(Request $request, Closure $next)
    {
        $authenticatedUser = Auth::user();

        if ($request->role === 'admin') {
            return response()->json(['message' => 'You cannot create a user with the Administrator role'], 403);
        }

        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->role !== 'regional_admin') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        if ($authenticatedUser->role === 'regional_admin') {
            // Проверяем, если новый пользователь принадлежит к той же стране, что и региональный админ
            if ($authenticatedUser->country !== $request->country) {
                return response()->json(['message' => 'You can only add users in your country'], 403);
            }

            // Региональный админ может добавлять только кастомеров или владельцев устройств в своем городе
            if ($request->role !== 'customer' && $request->role !== 'device_owner') {
                return response()->json(['message' => 'You can only add customers or device owners'], 403);
            }

            // Проверяем, если новый пользователь принадлежит к тому же городу, что и региональный админ
            if ($authenticatedUser->city !== $request->city) {
                return response()->json(['message' => 'You can only add users in your city'], 403);
            }
        }

        return $next($request);
    }
}
