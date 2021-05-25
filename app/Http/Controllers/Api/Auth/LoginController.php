<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\User;

class LoginController extends BaseController
{
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->all())) {
            return $this->sendErrorResponse('Unauthorised', ['error'=>'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return $this->sendSuccessResponse(['user' => auth()->user(), 'token' => $accessToken], 'User login successfully.');
    }
}
