<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Actions\Auth\LoginUserAction;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginUserAction $action)
    {
        $result = $action->handle($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'token' => $result['token'],
        ], 200);
    }
}
