<?php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\UserId;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalRepository implements UserRepositoryInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function saveUser(User $user): bool
    {
        $userIdValue = $user->getUserId()->getValue();
        $session = $this->requestStack->getSession();
        $users = $session->get('users') ?? [];
        $users[$userIdValue] = $user;
        $session->set('users', $users);

        return true;
    }

    public function getUserById(UserId $userId): array
    {
        $result = [];
        $session = $this->requestStack->getSession();
        $userIdValue = $userId->getValue();
        $users = $session->get('users');

        if (\array_key_exists($userIdValue, $users)) {
            $result = [$users[$userIdValue]];
        }

        return $result;
    }

    public function getUsers(): array
    {
        $session = $this->requestStack->getSession();
        return $session->get('users');
    }
}
