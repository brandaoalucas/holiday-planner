<?php

namespace App\Swagger\Auth;

use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/api/v1/logout",
 *     summary="Logout User",
 *     description="Revoke the authenticated user's access token to log them out.",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     security={{"passport": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User logged out successfully.", description="Success message"),
 *             @OA\Property(property="data", type="array", @OA\Items(), description="Empty array as a placeholder")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized.", description="Error message indicating the user is not authenticated"),
 *             @OA\Property(property="data", type="array", @OA\Items(), description="Empty array as a placeholder")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Internal server error.", description="Generic server error message"),
 *             @OA\Property(property="data", type="array", @OA\Items(), description="Empty array as a placeholder")
 *         )
 *     )
 * )
 */
class Logout {}