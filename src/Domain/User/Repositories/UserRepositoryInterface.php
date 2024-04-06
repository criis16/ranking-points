<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\User;
use App\Domain\User\UserId;

interface UserRepositoryInterface
{
    /**
     * Saves user score
     *
     * @param User $user
     * @return boolean
     */
    public function saveUser(User $user): bool;

    /**
     * Returns a user given an id
     *
     * @param UserId $userId
     * @return array
     */
    public function getUserById(UserId $userId): array;

    /**
     * Return an array of users
     *
     * @return User[]
     */
    public function getUsers(): array;
}
