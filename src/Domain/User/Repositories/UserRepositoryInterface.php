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
    public function saveUser(User $user): void;

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

    /**
     * Returns an array of top N users
     *
     * @param integer $top
     * @return array
     */
    public function getTopUsers(int $top): array;

    /**
     * Returns an array of users given a range
     *
     * @param integer $position
     * @param integer $range
     * @return array
     */
    public function getUserByRange(int $position, int $range): array;
}
