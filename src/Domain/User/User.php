<?php

namespace App\Domain\User;

use App\Domain\Score\Score;
use App\Domain\User\UserId;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class User
{
    private const EMPTY_SCORE = 0;

    private UserId $userId;
    private Score $userScore;

    public function __construct(
        UserId $userId,
        Score $userScore
    ) {
        $this->userId = $userId;
        $this->userScore = $userScore;
    }

    public static function create(UserId $userId): self
    {
        return new self(
            $userId,
            new AbsoluteScore(
                new ScorePoints(self::EMPTY_SCORE)
            )
        );
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

    public function setAbsoluteScore(ScorePoints $scorePoints): void
    {
        $absoluteScore = new AbsoluteScore($scorePoints);
        $absoluteScore->update($this->userScore);
        $this->userScore = $absoluteScore;
    }

    public function setRelativeScore(ScorePoints $scorePoints, RelativeScoreOperation $operation): void
    {
        $relativeScore = new RelativeScore($scorePoints);
        $relativeScore->setOperation($operation);
        $relativeScore->update($this->userScore);
        $this->userScore = $relativeScore;
    }
}
