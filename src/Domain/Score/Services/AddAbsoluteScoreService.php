<?php

namespace App\Domain\Score\Services;

use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\User\User;
use App\Domain\Score\ScorePoints;

class AddAbsoluteScoreService
{
    public function execute(
        User $user,
        ScorePoints $scorePoints
    ): User {
        $newUserScore = new AbsoluteScore($scorePoints);
        $user->addScore($newUserScore);

        return $user;
    }
}
