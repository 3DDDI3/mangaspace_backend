<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class AuthController extends Controller
{
    public function signin()
    {
        $user = User::create(request()->input());
    }
    public function login()
    {
        if (!Auth::attempt(request()->only('name', 'password'))) return;
        session()->regenerate();
        $user = User::where(request()->only('name'))->first();
        $token = $user->createToken('MyApp')->plainTextToken;
        $cookie = Cookie::make('_t', $token, 120, "/", "mangaspace.ru", false, false, false, false);
        return response()->json(['token' => $token], 200)->cookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'user token successfuly deleted'], 200);
    }

    public function check()
    {
        return response()->json(['user' => request()->user()], 200);
    }
}
