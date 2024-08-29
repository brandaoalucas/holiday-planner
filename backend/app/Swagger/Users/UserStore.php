<?php

namespace App\Swagger\Users;

use OpenApi\Annotations as OA;
 /**
 * @OA\Post(
 *     path="/api/v1/users",
 *     summary="Create a new user",
 *     operationId="createUser",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="Name of the user",
 *                 example="John Doe"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="Email address of the user",
 *                 example="johndoe@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 description="Password for the user account",
 *                 example="StrongPassword123!"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 description="Confirmation of the password",
 *                 example="StrongPassword123!"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="User created successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-15T12:34:56Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-15T12:34:56Z"),
 *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null),
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     description="Access token for the newly created user",
 *                     example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Validation failed."
 *             ),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 example={
 *                     "name": {
 *                         "The name field is required."
 *                     },
 *                     "email": {
 *                         "The email field is required.",
 *                         "The email must be a valid email address.",
 *                         "The email has already been taken."
 *                     },
 *                     "password": {
 *                         "The password field is required.",
 *                         "The password must be at least 8 characters.",
 *                         "The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.",
 *                         "The password confirmation does not match."
 *                     },
 *                     "password_confirmation": {
 *                         "The password confirmation is required."
 *                     }
 *                 }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized access",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Unauthenticated."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Access to the resource is forbidden",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Forbidden."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Internal server error."
 *             )
 *         )
 *     )
 * )
 */
class UserStore {}
