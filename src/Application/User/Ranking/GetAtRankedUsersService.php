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


    public function execute(
        int $position,
        int $range,
    ): array {
        return $this->repository->getUserByRange($position - 1, $range);
    }
}
