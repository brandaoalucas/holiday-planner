<?php
namespace App\Repositories;

use App\Models\HolidayPlan;
use App\Repositories\HolidayPlanRepositoryInterface;

class HolidayPlanRepository implements HolidayPlanRepositoryInterface
{
    public function __construct(
        protected HolidayPlan $holidayPlan
    ){}

    public function getAll(): array
    {
        return $this->holidayPlan->all()->toArray();
    }

    public function getAllByUserId(int $userId): array
    {
        return $this->holidayPlan->where('user_id', $userId)->get()->toArray();
    }

    public function findById(int $id): ?HolidayPlan
    {
        return $this->holidayPlan->find($id);
    }

    public function findByIdTrashed(int $id): ?HolidayPlan
    {
        return $this->holidayPlan->onlyTrashed()->find($id);
    }

    public function create(array $data): ?HolidayPlan
    {
        return $this->holidayPlan->create($data);
    }

    public function update(HolidayPlan $plan, array $data): bool
    {
        return $plan->update($data);
    }

    public function delete(HolidayPlan $plan): bool
    {
        return $plan->delete();
    }

    public function restore(HolidayPlan $plan): bool
    {
        return $plan->restore();
    }

    public function getAllOnlyTrashed(): array
    {
        return $this->holidayPlan->onlyTrashed()->get()->toArray();
    }

    public function getAllOnlyTrashedByUserId(int $userId): array
    {
        return $this->holidayPlan->onlyTrashed()->where('user_id', $userId)->get()->toArray();
    }
}
