<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll(): array;
    public function findById(int $id): ?User;
    public function findByIdTrashed(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function create(array $data): ?User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
    public function restore(User $user): bool;
    public function getAllOnlyTrashed(): array;
}
