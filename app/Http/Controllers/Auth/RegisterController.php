<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\RegisterRequest;
class RegisterController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action)
    {
        $user = $action->handle($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
