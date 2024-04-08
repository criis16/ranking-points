<?php

namespace App\Domain\Score;

use App\Domain\Score\ScoreType;
use App\Domain\Score\ScorePoints;
use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

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

    /**
     * Updates the score given the new score
     *
     * @param Score $score
     * @return void
     */
    public function update(Score $score): void
    {
        $userPointsValue = $this->getPoints()->getValue();
        $newPointsValue = $score->getPoints()->getValue();

        if ($score instanceof RelativeScore) {
            $operation = $score->getOperation()->getValue();
        } else {
            $operation = RelativeScoreOperation::ADD_OPERATION;
        }

        $updatedPoints = $this->performOperation(
            $userPointsValue,
            $newPointsValue,
            $operation
        );

        $this->setPoints(
            new ScorePoints($updatedPoints)
        );
    }

    /**
     * Performs the given operation
     *
     * @param integer $userPoints
     * @param integer $newPoints
     * @param string $operation
     * @return integer
     */
    private function performOperation(
        int $userPoints,
        int $newPoints,
        string $operation
    ): int {
        switch ($operation) {
            case RelativeScoreOperation::ADD_OPERATION:
                return $userPoints + $newPoints;
            case RelativeScoreOperation::SUBSTRACT_OPERATION:
                return $userPoints - $newPoints;
            default:
                throw new \InvalidArgumentException('Invalid operation type.');
        }
    }
}
