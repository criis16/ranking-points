<?php

namespace App\Domain\Score\AbsoluteScore;

use App\Domain\Score\Score;
use App\Domain\Score\ScoreType;
use App\Domain\Score\ScorePoints;

class AbsoluteScore extends Score
{
    public function __construct(
        ScorePoints $points
    ) {
        parent::__construct(
            $points,
            ScoreType::instance(ScoreType::ABSOLUTE_SCORE_TYPE)
        );
    }
}
