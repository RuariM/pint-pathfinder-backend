<?php

namespace App\Services;

use App\Contracts\AuthenticationServiceInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * Class constructor.
     *
     * @return void
     */

    public function login(LoginRequest $loginRequest)
    {
    }

    public function register(RegisterRequest $registerRequest)
    {
        return User::create([
             'first_name' => $registerRequest->first_name,
             'last_name' => $registerRequest->last_name,
             'username' => $registerRequest->username,
             'email' => $registerRequest->email,
             'password' => Hash::make($registerRequest->password)
         ]);
    }

    public function logout()
    {
        return true;
    }
}
