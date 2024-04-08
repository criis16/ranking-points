<?php

namespace App\Application\User\Ranking;

use App\Domain\User\Repositories\UserRepositoryInterface;

class GetAtRankedUsersService
{
    private UserRepositoryInterface $repository;

    public function __construct(
        UserRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns an array of users.
     * The array returned is formed by the user of the given position
     * and the users around it given the range number
     *
     * @param integer $position
     * @param integer $range
     * @return array
     */
    public function execute(
        int $position,
        int $range,
    ): array {
        return $this->repository->getUserByRange($position - 1, $range);
    }
}
