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

        if ($authenticatedUser->role !== 'admin') {
            return response()->json(['message' => 'У вас нет прав для выполнения этого действия.'], 403);
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