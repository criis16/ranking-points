<?php

namespace App\Domain\Score\RelativeScore;

use App\Domain\Score\Score;
use App\Domain\Score\ScoreType;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class RelativeScore extends Score
{
    private RelativeScoreOperation $operation;

    public function __construct(
        ScorePoints $points
    ) {
        parent::__construct(
            $points,
            ScoreType::instance(ScoreType::RELATIVE_SCORE_TYPE)
        );
    }

    /**
     * Get the value of operation
     */
    public function getOperation(): RelativeScoreOperation
    {
        return $this->operation;
    }

    /**
     * Set the value of operation
     *
     * @param RelativeScoreOperation $operation
     * @return void
     */
    public function setOperation(RelativeScoreOperation $operation): void
    {
        $this->operation = $operation;
    }
}
