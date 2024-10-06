<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $user = User::create($request->only(['name', 'password']));
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::query()
            ->where(['name' => $request->name])
            ->first();

        if (!Hash::check($request->password, $user->password))
            return response()->json(['message' => 'Неверное имя и/или пароль'], 401);

        Auth::login($user);

        $token = $user->createToken('MyApp');

        $agent = new Agent();

        $position = Location::get('176.59.2.222');

        // $device = $agent->device();
        // $platform = $agent->platform();
        // $browser = $agent->browser();
        // $isMobile = $agent->isMobile();
        // $isTablet = $agent->isTablet();
        // $isDesktop = $agent->isDesktop();

        DeviceToken::create([
            'personal_access_token_id',
            'client',
            'name',
            'operation_system',
            'country',
            'city'
        ]);

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response();
    }

    public function check(Request $request)
    {
        return response()->json(['user' => request()->user()], 200);
    }
}
