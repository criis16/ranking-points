<?php

namespace App\Application\User\AddUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\Score\ScorePoints;
use App\Application\User\GetUsers\GetUserByIdService;
use App\Infrastructure\User\Repositories\AddUserRequest;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class AddUserService
{
    private UserRepositoryInterface $repository;
    private GetUserByIdService $getUserByIdService;

    public function __construct(
        UserRepositoryInterface $repository,
        GetUserByIdService $getUserByIdService
    ) {
        $this->repository = $repository;
        $this->getUserByIdService = $getUserByIdService;
    }

    /**
     * Saves a score to a given user
     *
     * @param AddUserRequest $request
     * @return void
     */
    public function execute(
        AddUserRequest $request
    ): void {
        $userId = $request->getId();
        $user = $this->getUserByIdService->execute($userId);
        $user = empty($user) ? User::create(new UserId($userId)) : \reset($user);

        $totalScore = $request->getTotalScore();
        if (isset($totalScore)) {
            $user->setAbsoluteScore(new ScorePoints($totalScore));
        } else {
            $user->setRelativeScore(
                new ScorePoints($request->getRelativeScore()),
                RelativeScoreOperation::instance($request->getOperation())
            );
        }

        $this->repository->saveUser($user);
    }
}
