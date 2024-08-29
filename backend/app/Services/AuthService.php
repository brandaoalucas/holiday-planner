<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthService
{
    /**
     * Authenticate User and generate a token
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('token')->accessToken;

        return compact('user', 'token');
    }

    /**
     * Create a new user and generate a token
     * @param array $data
     * @return array
     */
    public function signup(array $data): array
    {
        $user = User::create([
            'name' => $data["name"],
            'email' => $data["email"],
            'role'  => 'user',
            'password' => Hash::make($data['password'])
        ]);
        /** @var User $user */
        $token = $user->createToken('token')->accessToken;

        return compact('user', 'token');
    }

    /**
     * Revoke user token.
     * @param User $user
     * @return bool
     */
    public function logout(User $user): bool
    {
        return $user->token()->revoke();
    }
}
