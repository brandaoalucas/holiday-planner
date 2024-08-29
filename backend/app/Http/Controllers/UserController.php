<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        protected UserService $userService
    ){}

    /**
     * Display a listing of the users
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $users = $this->userService->getAll();
        if (empty($users)) {
            return response()->json(['message' => 'No users found.', 'data' => []], 204);
        }
        return response()->json(['message' => 'Users retrieved successfully.', 'data' => $users], 200);
    }

    /**
     * Display the specified user.
     * @param int $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);
        if (empty($user)) {
            return response()->json(['message' => 'User not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'User retrieved successfully.', 'data' => $user], 200);
    }

    /**
     * Display the specified trashed user.
     * @param int $id
     * @return JsonResponse
     */
    public function findByIdTrashed(int $id): JsonResponse
    {
        $user = $this->userService->findByIdTrashed($id);
        if (empty($user)) {
            return response()->json(['message' => 'User not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'User retrieved successfully.', 'data' => $user], 200);
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return response()->json(['message' => 'User created successfully.', 'data' => $user], 201);
    }

    /**
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $updated = $this->userService->update($id, $request->validated());
        if (!$updated) {
            return response()->json(['message' => 'User not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'User updated successfully.', 'data' => []], 204);
    }

    /**
     * Delete the specified user in storage.
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->userService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'User not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'User deleted successfully.', 'data' => []], 204);
    }

    /**
     * Restore the specified user from soft-deletion.
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $restored = $this->userService->restore($id);
        if (!$restored) {
            return response()->json(['message' => 'User not found.', 'data' => []], 404);
        }
        return response()->json(['message' => 'User restored successfully.', 'data' => []], 204);
    }

    /**
     * Display a listing of only soft-deleted users.
     * @return JsonResponse
     */
    public function getAllOnlyTrashed(): JsonResponse
    {
        $trashedUsers = $this->userService->getAllOnlyTrashed();
        if (empty($trashedUsers)) {
            return response()->json(['message' => 'Soft-deleted users not found.', 'data' => []], 204);
        }
        return response()->json(['message' => 'Soft-deleted users retrieved successfully.', 'data' => $trashedUsers], 200);
    }
}
