<?php

namespace App\Domain\Score;

use App\Domain\Score\ScoreType;
use App\Domain\Score\ScorePoints;

abstract class Score
{
    private ScorePoints $points;
    private ScoreType $type;

    public function __construct(
        ScorePoints $points,
        ScoreType $type
    ) {
        $this->points = $points;
        $this->type = $type;
    }

    /**
     * Get the domain object of points
     *
     * @return ScorePoints
     */
    public function getPoints(): ScorePoints
    {
        return $this->points;
    }

    /**
     * Set the value of points
     *
     * @param ScorePoints $points
     * @return void
     */
    public function setPoints(ScorePoints $points): void
    {
        $this->points = $points;
    }

    /**
     * Get the domain object of type
     *
     * @return ScoreType
     */
    public function getType(): ScoreType
    {
        return $this->type;
    }

    abstract public function update(Score $score): void;
}
