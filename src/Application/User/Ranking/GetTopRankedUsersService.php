<?php

namespace App\Application\User\Ranking;

use App\Domain\User\Repositories\UserRepositoryInterface;

class GetTopRankedUsersService
{
    private UserRepositoryInterface $repository;

    public function __construct(
        UserRepositoryInterface $repository,
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns an array of the top N users
     *
     * @param integer $top
     * @return array
     */
    public function execute(
        int $top
    ): array {
        return $this->repository->getTopUsers($top);
    }
}
