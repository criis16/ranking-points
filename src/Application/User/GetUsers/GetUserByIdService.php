<?php

namespace App\Application\User\GetUsers;

use App\Domain\User\UserId;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUserByIdService
{
    private UserRepositoryInterface $repository;

    public function __construct(
        UserRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns a user given an id
     *
     * @param string $userId
     * @return array
     */
    public function execute(string $userId): array
    {
        $userId = new UserId($userId);
        return $this->repository->getUserById($userId);
    }
}
