<?php

namespace App\Application\User\GetUsers;

use App\Domain\User\User;
use App\Application\User\Adapters\UserAdapter;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUsersService
{
    private UserRepositoryInterface $repository;
    private UserAdapter $adapter;

    public function __construct(
        UserRepositoryInterface $repository,
        UserAdapter $adapter
    ) {
        $this->repository = $repository;
        $this->adapter = $adapter;
    }

    /**
     * Returns an array of users
     *
     * @return array
     */
    public function execute(): array
    {
        $users = $this->repository->getUsers();
        return \array_map(
            function (User $user) {
                return $this->adapter->adapt($user);
            },
            \array_values($users)
        );
    }
}
