<?php

namespace Tests\Domain\Score;

use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\RelativeScore\RelativeScore;

class ScoreTest extends TestCase
{
    private RelativeScore $sut;

    /** @var ScorePoints&MockObject */
    private ScorePoints $points;

    /** @var ScoreType&MockObject */
    private ScoreType $type;

    protected function setUp(): void
    {
        $this->points = $this->createMock(ScorePoints::class);
        $this->type = $this->createMock(ScoreType::class);
        $this->sut = new RelativeScore(
            $this->points,
            $this->type
        );
    }

    public function testGetScorePoints(): void
    {
        self::assertEquals($this->points, $this->sut->getPoints());
    }

    public function testGetScoreType(): void
    {
        self::assertEquals(ScoreType::RELATIVE_SCORE_TYPE, $this->sut->getType()->getValue());
    }
}
