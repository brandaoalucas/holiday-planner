<?php

namespace App\Swagger\Users;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v1/users/{id}/show",
 *     summary="Retrieve a user by ID",
 *     operationId="getUserById",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to retrieve",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="User retrieved successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-08-15T12:34:56Z"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-15T12:34:56Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-15T12:34:56Z"),
 *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
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
class UserShow {}
