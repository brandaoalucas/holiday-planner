<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $user
    ){}

    public function getAll(): array
    {
        return $this->user->all()->toArray();
    }

    public function findById(int $id): ?User
    {
        return $this->user->find($id);
    }

    public function findByIdTrashed(int $id): ?User
    {
        return $this->user->onlyTrashed()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function create(array $data): ?User
    {
        return $this->user->create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function restore(User $user): bool
    {
        return $user->restore();
    }

    public function getAllOnlyTrashed(): array
    {
        return $this->user->onlyTrashed()->get()->toArray();
    }
}
