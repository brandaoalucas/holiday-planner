<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Authenticate User
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if (!$result) {
            return response()->json(['message' => 'Provided email or password is incorrect'], 422);
        }

        return response()->json($result, 200);
    }

    /**
     * Create user and set token
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->authService->signup($data);

        return response()->json($result, 201);
    }

    /**
     * Return authenticated user.
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['message' => 'Unauthorized.', 'data' => []], 401);
        }
        return response()->json(compact('user'), 200);
    }

    /**
     * Revoke user token.
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->authService->logout($user);

        return response()->json(['message' => 'User logged out successfully.', 'data' => []], 200);
    }
}