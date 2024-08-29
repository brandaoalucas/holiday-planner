<?php

namespace App\Swagger\Auth;

use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Authenticate User",
 *     description="Authenticate a user using email and password to obtain an access token.",
 *     operationId="login",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User's email address"),
 *             @OA\Property(property="password", type="string", example="password123!", description="User's password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1, description="User ID"),
 *                 @OA\Property(property="name", type="string", example="John Doe", description="User's name"),
 *                 @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User's email address"),
 *                 @OA\Property(property="role", type="string", example="user", description="User role"),
 *             ),
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9", description="Access token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error or incorrect credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Provided email or password is incorrect", description="Error message")
 *         )
 *     )
 * )
 */
class Login {}