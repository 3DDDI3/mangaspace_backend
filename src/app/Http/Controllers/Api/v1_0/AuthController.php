<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserCreateRequest;
use App\Models\User;
use App\Services\UserDeviceInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $userDevice;

    public function __construct()
    {
        $this->userDevice = new UserDeviceInformation();
    }

    /**
     * Регистрация пользователя
     *
     * @param Request $request
     * @return void
     */
    public function signup(UserCreateRequest $request)
    {
        $data = $request->validated();
        return response($data, 200);

        // $user = User::create($request->only(['name', 'password', 'email']));
    }

    /**
     * Авторизация пользователя
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::query()
            ->where(['name' => $request->name])
            ->first();

        if (!$user)
            return response()->json(['message' => 'Неверное имя и/или пароль'], 401);

        if (!Hash::check($request->password, $user->password))
            return response()->json(['message' => 'Неверное имя и/или пароль'], 401);

        Auth::login($user);

        $token = $user->createToken('MyApp');

        // $this->userDevice->getDeviceInformation($request, $token->accessToken);

        return response(['token' => $token->plainTextToken], 201);
    }

    /**
     * Выход из акаунта
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response();
    }

    public function check(Request $request)
    {
        return response(['user' => $request->user()], 200);
        // $response = Gate::inspect('view', request()->user());
        // if (!$response->allowed())
        //     abort(404);
    }
}
