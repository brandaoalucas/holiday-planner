<?php

namespace App\Repositories;

use App\Models\HolidayPlan;

interface HolidayPlanRepositoryInterface
{
    public function getAll(): array;
    public function getAllByUserId(int $userId): array;
    public function findById(int $id): ?HolidayPlan;
    public function findByIdTrashed(int $id): ?HolidayPlan;
    public function create(array $data): ?HolidayPlan;
    public function update(HolidayPlan $plan, array $data): bool;
    public function delete(HolidayPlan $plan): bool;
    public function restore(HolidayPlan $plan): bool;
    public function getAllOnlyTrashed(): array;
    public function getAllOnlyTrashedByUserId(int $userId): array;
}
