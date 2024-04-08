<?php

namespace App\Domain\Score\Services;

use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\User\User;
use App\Domain\Score\ScorePoints;

class AddAbsoluteScoreService
{
    /**
     * Adds a score to a given user
     *
     * @param User $user
     * @param ScorePoints $scorePoints
     * @return User
     */
    public function execute(
        User $user,
        ScorePoints $scorePoints
    ): User {
        $user->addScore(new AbsoluteScore($scorePoints));

        return $user;
    }
}
