<?php

namespace App\Domain\Score\Services;

use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\User\User;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class AddRelativeScoreService
{
    /**
     * Updates the score to a given user depending on the operation given
     *
     * @param User $user
     * @param ScorePoints $scorePoints
     * @param RelativeScoreOperation $operation
     * @return User
     */
    public function execute(
        User $user,
        ScorePoints $scorePoints,
        RelativeScoreOperation $operation
    ): User {
        $newUserScore = new RelativeScore($scorePoints);
        $newUserScore->setOperation($operation);
        $user->addScore($newUserScore);

        return $user;
    }
}
