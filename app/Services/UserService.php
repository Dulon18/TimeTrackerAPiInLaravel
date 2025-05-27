<?php

namespace App\Services;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class UserService implements UserInterface
{
    public function register(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(array $users)
    {
        $user = User::where('email', $users['email'])->first();
        if (! $user || ! Hash::check($users['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken($user->name.'api_token')->plainTextToken;

        return [
            'email' => $user->email,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];
    }
}
