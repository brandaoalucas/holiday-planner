<?php

namespace App\Swagger\HolidayPlans;

use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/v1/holiday-plans/{id}",
 *     summary="Update an existing holiday plan",
 *     operationId="updateHolidayPlan",
 *     tags={"Holiday Plans"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the holiday plan to be updated",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Title of the holiday plan",
 *                 example="Summer Vacation",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Description of the holiday plan",
 *                 example="A 15-day trip to the beach.",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 format="date",
 *                 description="Date of the holiday plan",
 *                 example="2024-06-15",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="location",
 *                 type="string",
 *                 description="Location of the holiday plan",
 *                 example="Bahamas",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="participants",
 *                 type="array",
 *                 description="List of participants (optional)",
 *                 @OA\Items(
 *                     type="string",
 *                     example="John Doe"
 *                 ),
 *                 nullable=true
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Holiday plan updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Holiday plan updated successfully."
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
 *         description="Holiday plan not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Holiday plan not found."
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
 *                     "title": {
 *                         "The title must be a valid string if provided.",
 *                         "The title cannot exceed 255 characters if provided."
 *                     },
 *                     "description": {
 *                         "The description must be a valid string if provided."
 *                     },
 *                     "date": {
 *                         "The date must be in the correct format: YYYY-MM-DD if provided.",
 *                         "The date must be today or a future date if provided."
 *                     },
 *                     "location": {
 *                         "The location must be a valid string if provided.",
 *                         "The location cannot exceed 255 characters if provided."
 *                     },
 *                     "participants": {
 *                         "Participants must be an array if provided.",
 *                         "There must be at least one participant if the participants field is provided."
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

 class HolidayPlanUpdate {}
