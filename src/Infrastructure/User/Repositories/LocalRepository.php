<?php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\UserId;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalRepository implements UserRepositoryInterface
{
    private const FIRST_OFFSET = 0;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function saveUser(User $user): void
    {
        $userIdValue = $user->getUserId()->getValue();
        $session = $this->requestStack->getSession();
        $users = $session->get('users') ?? [];
        $users[$userIdValue] = $user;
        $session->set('users', $users);
    }

    public function getUserById(UserId $userId): array
    {
        $result = [];
        $session = $this->requestStack->getSession();
        $userIdValue = $userId->getValue();
        $users = $session->get('users') ?? [];

        if (\array_key_exists($userIdValue, $users)) {
            $result = [$users[$userIdValue]];
        }

        return $result;
    }

    public function getUsers(): array
    {
        $session = $this->requestStack->getSession();
        $users = $session->get('users') ?? [];

        if (!empty($users)) {
            $users = $this->sortUsersByScore($users);
        }

        return $users;
    }

    public function getTopUsers(int $top): array
    {
        $session = $this->requestStack->getSession();
        $users = $session->get('users') ?? [];

        if (!empty($users)) {
            $users = $this->sortUsersByScore($users);
        }

        return \array_slice($users, self::FIRST_OFFSET, $top);
    }

    public function getUserByRange(int $position, int $range): array
    {
        $session = $this->requestStack->getSession();
        $users = $session->get('users') ?? [];

        if (!empty($users)) {
            $users = $this->sortUsersByScore($users);
        }

        if (!\array_key_exists($position, $users)) {
            return [];
        }

        $start = max(0, $position - $range);
        $end = min(count($users) - 1, $position + $range);

        return \array_slice($users, $start, $end - $start + 1);
    }

    /**
     * Sorts an array of users by their scores
     *
     * @param User[] $users
     * @return User[]
     */
    private function sortUsersByScore(array $users): array
    {
        usort(
            $users,
            function (User $firstUser, User $secondUser) {
                $firstUserScore = $firstUser->getScore()->getPoints()->getValue();
                $secondUserScore = $secondUser->getScore()->getPoints()->getValue();

                if ($firstUserScore == $secondUserScore) {
                    return 0;
                }

                return ($firstUserScore > $secondUserScore) ? -1 : 1;
            }
        );
        return $users;
    }
}
