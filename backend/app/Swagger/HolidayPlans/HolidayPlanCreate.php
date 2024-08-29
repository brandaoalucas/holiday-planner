<?php

namespace App\Swagger\HolidayPlans;

use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/api/v1/holiday-plans",
 *     summary="Create a new holiday plan",
 *     operationId="createHolidayPlan",
 *     tags={"Holiday Plans"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Title of the holiday plan",
 *                 example="Summer Vacation"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Description of the holiday plan",
 *                 example="A 15-day trip to the beach."
 *             ),
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 format="date",
 *                 description="Date of the holiday plan",
 *                 example="2024-06-15"
 *             ),
 *             @OA\Property(
 *                 property="location",
 *                 type="string",
 *                 description="Location of the holiday plan",
 *                 example="Bahamas"
 *             ),
 *             @OA\Property(
 *                 property="participants",
 *                 type="array",
 *                 description="List of participants (optional)",
 *                 @OA\Items(
 *                     type="string",
 *                     example="John Doe"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Holiday plan created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Holiday plan created successfully."
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Summer Vacation"),
 *                 @OA\Property(property="description", type="string", example="A 15-day trip to the beach."),
 *                 @OA\Property(property="date", type="string", format="date", example="2024-06-15"),
 *                 @OA\Property(property="location", type="string", example="Bahamas"),
 *                 @OA\Property(
 *                     property="participants",
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         example="John Doe"
 *                     )
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T00:00:00Z"),
 *                 @OA\Property(property="deleted_at", type="string", format="date-time", example="NULL")
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
 *                         "The title is mandatory and cannot be empty."
 *                     },
 *                     "description": {
 *                         "The description is required and cannot be left blank."
 *                     },
 *                     "date": {
 *                         "The date is required and must be in the format YYYY-MM-DD.",
 *                         "The date must be today or a future date."
 *                     },
 *                     "location": {
 *                         "The location is mandatory and cannot be empty.",
 *                         "The location cannot exceed 255 characters."
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
class HolidayPlanCreate {}
