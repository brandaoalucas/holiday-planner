<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected User $user;
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ){
        $this->user = auth()->user();
    }

    public function getAll(): array
    {
        if ($this->user->isAdmin()) {
            return $this->userRepository->getAll();
        }
        return [];
    }

    public function findById(int $id): ?User
    {
        $user = $this->userRepository->findById($id);
        return $user && $this->hasAccess($user) ? $user : null;
    }

    public function findByIdTrashed(int $id): ?User
    {
        $user = $this->userRepository->findByIdTrashed($id);
        return $user && $this->hasAccess($user) ? $user : null;
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        return $user && $this->hasAccess($user) ? $user : null;
    }

    public function create(array $data): ?User
    {
        if (isset($data['password']) && !empty($data['password'])){
            Hash::make($data['password']);
        }

        if (!$this->user->isAdmin()) {
            $data['role'] = 'user';
        }

        $user = $this->userRepository->create($data);
        if ($user) {
            $user->token = $user->createToken('ACCESS_TOKEN')->accessToken;
        }
        
        return $user;
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            if (!empty($data['password'])) {
                Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
        }

        if (!$this->user->isAdmin()) {
            $data['role'] = 'user';
        }

        $user = $this->findById($id);
        return $user ? $this->userRepository->update($user, $data) : false;
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        return $user ? $this->userRepository->delete($user) : false;
    }

    public function restore(int $id): bool
    {
        $user = $this->findByIdTrashed($id);
        return $user ? $this->userRepository->restore($user) : false;
    }

    public function getAllOnlyTrashed(): array
    {
        if ($this->user->isAdmin()) {
            return $this->userRepository->getAllOnlyTrashed();
        }
        return [];
    }

    protected function hasAccess(User $user): bool
    {
        return $this->user->isAdmin() || $this->user->id === $user->id;
    }
}
