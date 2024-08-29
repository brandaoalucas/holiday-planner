<?php

namespace App\Swagger\HolidayPlans;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HolidayPlan",
 *     required={"title", "description", "date", "location"},
 *     title="Holiday Plan",
 *     description="Model representation of a Holiday Plan",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the holiday plan",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the holiday plan",
 *         example="Summer Vacation"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Detailed description of the holiday plan",
 *         example="A 15-day trip to the beach with family."
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="Date of the holiday plan",
 *         example="2024-06-15"
 *     ),
 *     @OA\Property(
 *         property="location",
 *         type="string",
 *         description="Location of the holiday plan",
 *         example="Bahamas"
 *     ),
 *     @OA\Property(
 *         property="participants",
 *         type="array",
 *         description="List of participants in the holiday plan",
 *         @OA\Items(
 *             type="string",
 *             example="John Doe"
 *         ),
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the holiday plan was created",
 *         example="2024-06-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the holiday plan was last updated",
 *         example="2024-06-10T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the holiday plan was soft-deleted",
 *         example=null,
 *         nullable=true
 *     )
 * )
 */
class HolidayPlanSchema {}
