<?php

namespace App\Swagger\HolidayPlans;

use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/api/v1/holiday-plan/{id}/pdf",
 *     summary="Generate Holiday Plan PDF",
 *     description="Generate and download a PDF file of the holiday plan with the specified ID.",
 *     operationId="generateHolidayPlanPDF",
 *     tags={"Holiday Plans"},
 *     security={{"passport": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the holiday plan to generate the PDF for",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             example=1
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="PDF file generated successfully",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/pdf",
 *                 @OA\Schema(
 *                     type="string",
 *                     format="binary",
 *                     description="PDF binary stream"
 *                 )
 *             )
 *         }
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Holiday Plan not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Holiday Plan not found", description="Error message when the plan is not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized.", description="Error message indicating the user is not authenticated")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Internal server error.", description="Generic server error message")
 *         )
 *     )
 * )
 */
class HolidayPlanPdf {}
