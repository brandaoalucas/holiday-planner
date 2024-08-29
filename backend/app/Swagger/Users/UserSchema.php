<?php

namespace App\Swagger\Users;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     required={"name", "email", "password"},
 *     @OA\Property(
 *         property="id",
 *         description="User ID",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         description="User name",
 *         type="string",
 *         example="Jhon Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         description="User email",
 *         type="string",
 *         format="email",
 *         example="test@example.com"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         description="Email verification timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-15T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         description="User password",
 *         type="string",
 *         format="password",
 *         example="hashedpassword"
 *     ),
 *     @OA\Property(
 *         property="remember_token",
 *         description="Remember token",
 *         type="string",
 *         example="3Thk2qjt5PhPZKYp8I1J"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="User creation timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-15T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="User update timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-15T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         description="User soft deletion timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-15T12:34:56Z"
 *     )
 * )
 */
class UserSchema {}
