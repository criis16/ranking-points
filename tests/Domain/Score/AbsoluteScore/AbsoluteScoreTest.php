<?php

namespace Tests\Domain\Score\AbsoluteScore;

use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;

class AbsoluteScoreTest extends TestCase
{
    private AbsoluteScore $sut;

    /** @var ScorePoints&MockObject */
    private ScorePoints $scorePoints;

    protected function setUp(): void
    {
        $this->scorePoints = $this->createMock(ScorePoints::class);
        $this->sut = new AbsoluteScore(
            $this->scorePoints
        );
    }

    public function testGetScorePoints(): void
    {
        self::assertEquals($this->scorePoints, $this->sut->getPoints());
    }

    public function testGetScoreType(): void
    {
        self::assertEquals(ScoreType::ABSOLUTE_SCORE_TYPE, $this->sut->getType()->getValue());
    }
}
