<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Auth;

class UserService
{

    /**
     * Create user
     * @param $userData
     * @throws \Exception
     * @return User
     */
    public function createUser($userData): User
    {
        DB::beginTransaction();
        try {
            $oUser = User::create([
                    'name' => Arr::get($userData, 'name'),
                    'email' => Arr::get($userData, 'email'),
                    'password' => bcrypt(Arr::get($userData, 'password'))
                ]);
            DB::commit();
            return $oUser;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception('Not able to create user', 422);
        }
    }
}
