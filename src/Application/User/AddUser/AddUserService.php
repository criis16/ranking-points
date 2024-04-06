<?php

namespace App\Application\User\AddUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use InvalidArgumentException;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\Services\AddRelativeScoreService;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;
use App\Infrastructure\User\Repositories\AddUserRequest;

class AddUserService
{
    private UserRepositoryInterface $repository;
    private AddRelativeScoreService $addRelativeScoreService;

    public function __construct(
        UserRepositoryInterface $repository,
        AddRelativeScoreService $addRelativeScoreService
    ) {
        $this->repository = $repository;
        $this->addRelativeScoreService = $addRelativeScoreService;
    }

    /**
     * Saves a score to a given user
     *
     * @param AddUserRequest $request
     * @return boolean
     */
    public function execute(
        AddUserRequest $request
    ): bool {
        $userId = new UserId($request->getId());
        $user = $this->checkUser($userId);

        if (!empty($user)) {
            if (!empty($request->getTotalScore())) {
                throw new InvalidArgumentException('Must be a relative score for an existing user, not absolute');
            }

            $user = $this->addRelativeScoreService->execute(
                \reset($user),
                new ScorePoints($request->getRelativeScore()),
                RelativeScoreOperation::instance($request->getOperation())
            );
        } else {
            if (!empty($request->getRelativeScore())) {
                throw new InvalidArgumentException('Must be an absolute score for a new user, not relative');
            }

            $user = new User(
                $userId,
                new AbsoluteScore(
                    new ScorePoints($request->getTotalScore())
                )
            );
        }

        return $this->repository->saveUser($user);
    }

    private function checkUser(UserId $userId): array
    {
        return $this->repository->getUserById($userId);
    }
}
