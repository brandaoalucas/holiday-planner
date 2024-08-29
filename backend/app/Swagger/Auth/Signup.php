<?php

namespace App\Swagger\Auth;

use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/api/v1/signup",
 *     summary="Create User Account",
 *     description="Register a new user and return an access token.",
 *     operationId="signup",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "password_confirmation"},
 *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User's email address"),
 *             @OA\Property(property="password", type="string", example="Password@123", description="User's password"),
 *             @OA\Property(property="password_confirmation", type="string", example="Password@123", description="Password confirmation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully",
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
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid.", description="Validation error message"),
 *             @OA\Property(property="errors", type="object",
 *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email has already been taken.")),
 *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password confirmation does not match.")),
 *             )
 *         )
 *     )
 * )
 */
class Signup {}