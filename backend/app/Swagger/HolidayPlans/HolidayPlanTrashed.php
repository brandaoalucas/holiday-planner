<?php

namespace App\Swagger\HolidayPlans;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v1/holiday-plans/trashed",
 *     summary="Get a list of soft-deleted holiday plans",
 *     operationId="getTrashedHolidayPlans",
 *     tags={"Holiday Plans"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Trashed holiday plans retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Trashed holiday plans retrieved successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="Summer Vacation"),
 *                     @OA\Property(property="description", type="string", example="A 15-day trip to the beach."),
 *                     @OA\Property(property="date", type="string", format="date", example="2024-06-15"),
 *                     @OA\Property(property="location", type="string", example="Bahamas"),
 *                     @OA\Property(property="participants", type="array",
 *                         @OA\Items(type="string", example="John Doe")
 *                     ),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T00:00:00Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T00:00:00Z"),
 *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2024-06-01T00:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No trashed holiday plans found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="No trashed holiday plans found."
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
class HolidayPlanTrashed {}
