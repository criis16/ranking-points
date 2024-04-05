<?php

namespace App\Domain\Score;

class ScorePoints
{
    public const ZERO_POINTS = 0;

    private int $points;

    public function __construct(int $points)
    {
        $this->points = ($points < 0) ? self::ZERO_POINTS : $points;
    }

    /**
     * Return the score points value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->points;
    }

    /**
     * Set the score points value
     *
     * @return void
     */
    public function setValue(int $points): void
    {
        $this->points = ($points < 0) ? self::ZERO_POINTS : $points;
    }
}
