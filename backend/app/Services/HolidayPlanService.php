<?php

namespace App\Services;

use App\Models\HolidayPlan;
use App\Repositories\HolidayPlanRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class HolidayPlanService
{
    protected $user;

    public function __construct(
        protected HolidayPlanRepositoryInterface $holidayPlanRepository,
    ){
        $this->user = auth()->user();
    }

    public function getAll(): array
    {
        if ($this->user->isAdmin()) {
            return $this->holidayPlanRepository->getAll();
        }
        return $this->getAllByUserId($this->user->id);
    }

    public function getAllByUserId(int $userId): array
    {
        if ($this->user->isAdmin() || $this->user->id === $userId) {
            return $this->holidayPlanRepository->getAllByUserId($userId);
        }
        return [];
    }

    public function findById(int $id): ?HolidayPlan
    {
        $plan = $this->holidayPlanRepository->findById($id);
        return $plan && $this->hasAcces($plan) ? $plan : null;
    }

    public function findByIdTrashed(int $id): ?HolidayPlan
    {
        $plan = $this->holidayPlanRepository->findByIdTrashed($id);
        return $plan && $this->hasAcces($plan) ? $plan : null;
    }

    public function create(array $data): ?HolidayPlan
    {
        $data['user_id'] = $this->user->id;
        return $this->holidayPlanRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $plan = $this->findById($id);
        $data['user_id'] = $this->user->id;
        return $plan ? $this->holidayPlanRepository->update($plan, $data) : false;
    }

    public function delete(int $id): bool
    {
        $plan = $this->findById($id);
        return $plan ? $this->holidayPlanRepository->delete($plan) : false;
    }

    public function restore(int $id): bool
    {
        $plan = $this->findByIdTrashed($id);
        return $plan ? $this->holidayPlanRepository->restore($plan) : false;
    }

    public function getAllOnlyTrashed(): array
    {
        if ($this->user->isAdmin()) {
            return $this->holidayPlanRepository->getAllOnlyTrashed();
        }
        return $this->getAllOnlyTrashedByUserId($this->user->id);
    }

    public function getAllOnlyTrashedByUserId(int $userId)
    {
        if ($this->user->isAdmin() || $this->user->id === $userId) {
            return $this->holidayPlanRepository->getAllOnlyTrashedByUserId($userId);
        }
        return [];
    }

    protected function hasAcces(HolidayPlan $plan): bool
    {
        return $this->user->isAdmin() || $this->user->id === $plan->user_id;
    }

    public function generateHolidayPlanPDF(int $id)
    {
        $holidayPlan = $this->findById($id);
        if ($holidayPlan) {
            $pdf = Pdf::loadView('pdf.holiday_plan', compact('holidayPlan'));
            // $pdf->loadView('pdf.holiday_plan', $holidayPlan->toArray());
            return $pdf->download('holiday_plan_' . now() . '.pdf');
        }
    }
}
