<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Registration
     */
    public function register(RegisterRequest $request, UserService $userService)
    {
        $user = $userService->createUser($request->all());
        $token = $user->createToken('authToken')->accessToken;
        return $this->sendSuccessResponse(['user' => $user,'token' => $token], 'User register successfully.', 201);
    }
}
