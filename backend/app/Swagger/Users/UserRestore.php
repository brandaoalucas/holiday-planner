<?php

namespace App\Swagger\Users;

use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/api/v1/users/{id}/restore",
 *     summary="Restore a soft-deleted user by ID",
 *     operationId="restoreUser",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to restore",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="User restored successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="User restored successfully."
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
class UserRestore {}
