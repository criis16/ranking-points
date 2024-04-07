<?php

namespace App\Application\User\AddUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\Services\AddRelativeScoreService;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;
use App\Domain\Score\Services\AddAbsoluteScoreService;
use App\Infrastructure\User\Repositories\AddUserRequest;

class AddUserService
{
    public const EMPTY_SCORE = 0;

    private UserRepositoryInterface $repository;
    private AddRelativeScoreService $addRelativeScoreService;
    private AddAbsoluteScoreService $addAbsoluteScoreService;

    public function __construct(
        UserRepositoryInterface $repository,
        AddRelativeScoreService $addRelativeScoreService,
        AddAbsoluteScoreService $addAbsoluteScoreService
    ) {
        $this->repository = $repository;
        $this->addRelativeScoreService = $addRelativeScoreService;
        $this->addAbsoluteScoreService = $addAbsoluteScoreService;
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
            $user = $this->updateUserScore($request, \reset($user));
        } else {
            $user = new User(
                $userId,
                new AbsoluteScore(
                    new ScorePoints(self::EMPTY_SCORE)
                )
            );

            $user = $this->updateUserScore($request, $user);
        }

        return $this->repository->saveUser($user);
    }

    private function checkUser(UserId $userId): array
    {
        return $this->repository->getUserById($userId);
    }

    private function updateUserScore(
        AddUserRequest $request,
        User $user
    ): User {
        $totalScore = $request->getTotalScore();
        if (isset($totalScore)) {
            $user = $this->addAbsoluteScoreService->execute(
                $user,
                new ScorePoints($totalScore)
            );
        } else {
            $user = $this->addRelativeScoreService->execute(
                $user,
                new ScorePoints($request->getRelativeScore()),
                RelativeScoreOperation::instance($request->getOperation())
            );
        }

        return $user;
    }
}
