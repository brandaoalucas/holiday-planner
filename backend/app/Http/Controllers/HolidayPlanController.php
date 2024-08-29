<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHolidayPlanRequest;
use App\Http\Requests\UpdateHolidayPlanRequest;
use App\Services\HolidayPlanService;
use Illuminate\Http\JsonResponse;

class HolidayPlanController extends Controller
{
    /**
     * @param HolidayPlanService $holidayPlanService
     */
    public function __construct(
        protected HolidayPlanService $holidayPlanService
    ){}

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $holidayPlans = $this->holidayPlanService->getAll();
        if (empty($holidayPlans)) {
            return response()->json(['message' => 'No holiday plans found.', 'data' => []], 204);
        }
        return response()->json(['message' => 'Holiday plans retrieved successfully.', 'data' => $holidayPlans], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse
    {
        $holidayPlan = $this->holidayPlanService->findById($id);
        if (empty($holidayPlan)) {
            return response()->json(['message' => 'Holiday plan not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Holiday plan retrieved successfully.', 'data' => $holidayPlan], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function findByIdTrashed(int $id): JsonResponse
    {
        $holidayPlan = $this->holidayPlanService->findByIdTrashed($id);
        if (empty($holidayPlan)) {
            return response()->json(['message' => 'Trashed holiday plan not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Trashed holiday plan retrieved successfully.', 'data' => $holidayPlan], 200);
    }

    /**
     * @param CreateHolidayPlanRequest $request
     * @return JsonResponse
     */
    public function create(CreateHolidayPlanRequest $request): JsonResponse
    {
        $holidayPlan = $this->holidayPlanService->create($request->validated());
        return response()->json(['message' => 'Holiday plan created successfully.', 'data' => $holidayPlan],  201);
    }

    /**
     * @param UpdateHolidayPlanRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateHolidayPlanRequest $request, int $id): JsonResponse
    {
        $updated = $this->holidayPlanService->update($id, $request->validated());
        if (!$updated) {
            return response()->json(['message' => 'Holiday plan not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Holiday plan updated successfully.', 'data' => []], 204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->holidayPlanService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Holiday plan not found.', 'data' => []], 404);
        }

        return response()->json(['message' => 'Holiday plan deleted successfully.', 'data' => []], 204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $restored = $this->holidayPlanService->restore($id);

        if (!$restored) {
            return response()->json(['message' => 'No trashed holiday plan found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'Trashed holiday plan restored successfully.', 'data' => []], 204);
    }

    /**
     * @return JsonResponse
     */
    public function getAllOnlyTrashed(): JsonResponse
    {
        $trashedPlans = $this->holidayPlanService->getAllOnlyTrashed();
        if (empty($trashedPlans)) {
            return response()->json(['message' => 'No trashed holiday plans found.', 'data' => []], 204);
        }
        return response()->json(['message' => 'Trashed holiday plans retrieved successfully.', 'data' => $trashedPlans], 200);
    }

    /**
     * @var int $id
     * @return BinaryFileResponse
     */
    public function generateHolidayPlanPDF(int $id)
    {
        return $this->holidayPlanService->generateHolidayPlanPDF($id);
    }
}
