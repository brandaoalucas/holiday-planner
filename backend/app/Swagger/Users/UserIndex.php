<?php

namespace App\Swagger\Users;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v1/users",
 *     summary="Retrieve list of users",
 *     operationId="getUsersList",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Users retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Users retrieved successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Test User"),
 *                     @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-08-15T00:05:56.000000Z"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-15T00:05:56.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-15T00:05:56.000000Z"),
 *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
 *                 ),
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="name", type="string", example="Another User"),
 *                     @OA\Property(property="email", type="string", format="email", example="another@example.com"),
 *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-08-16T00:05:56.000000Z"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-16T00:05:56.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-16T00:05:56.000000Z"),
 *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No users found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="No users found."
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
 *         response="500",
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
class UserIndex {}
