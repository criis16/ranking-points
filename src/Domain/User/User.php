<?php

namespace App\Domain\User;

use App\Domain\Score\Score;
use App\Domain\User\UserId;
use InvalidArgumentException;
use App\Domain\Score\ScoreType;

class User
{
    private UserId $userId;
    private Score $userScore;

    public function __construct(
        UserId $userId,
        Score $userScore
    ) {
        $this->userId = $userId;
        $this->userScore = $userScore;
    }

    /**
     * Get the domain object user id
     *
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * Get the domain object user score
     *
     * @return Score
     */
    public function getScore(): Score
    {
        return $this->userScore;
    }

    /**
     * Adds a score to the user
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function addScore(Score $score): void
    {
        $scoreTypeValue = $score->getType()->getValue();
        if (
            $scoreTypeValue === ScoreType::ABSOLUTE_SCORE_TYPE ||
            $scoreTypeValue === ScoreType::RELATIVE_SCORE_TYPE
        ) {
            $this->userScore->update($score);
        } else {
            throw new InvalidArgumentException('Score type not allowed: ' . $scoreTypeValue);
        }
    }
}
