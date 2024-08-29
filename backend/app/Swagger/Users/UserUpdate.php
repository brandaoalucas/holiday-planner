<?php

namespace App\Swagger\Users;

use OpenApi\Annotations as OA;
 /**
 * @OA\Put(
 *     path="/api/v1/users/{id}",
 *     summary="Update an existing user",
 *     operationId="updateUser",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
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
 *                 description="Password for the user account (optional)",
 *                 example="StrongPassword123!"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 description="Confirmation of the password (required if password is provided)",
 *                 example="StrongPassword123!"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="User updated successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 example="[]",
 *                 @OA\Items(type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="User not found."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 example="[]",
 *                 @OA\Items(type="string")
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
 *                         "The password must be at least 8 characters.",
 *                         "The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.",
 *                         "The password confirmation does not match."
 *                     },
 *                     "password_confirmation": {
 *                         "The password confirmation is required when the password is present."
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
class UserUpdate {}
