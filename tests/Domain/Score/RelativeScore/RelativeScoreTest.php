<?php

namespace Tests\Domain\Score\RelativeScore;

use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class RelativeScoreTest extends TestCase
{
    private RelativeScore $sut;

    /** @var ScorePoints&MockObject */
    private ScorePoints $scorePoints;

    /** @var RelativeScoreOperation&MockObject */
    private RelativeScoreOperation $scoreOperation;

    protected function setUp(): void
    {
        $this->scorePoints = $this->createMock(ScorePoints::class);
        $this->scoreOperation = $this->createMock(RelativeScoreOperation::class);
        $this->sut = new RelativeScore(
            $this->scorePoints
        );

        $this->sut->setOperation($this->scoreOperation);
    }

    public function testGetScorePoints(): void
    {
        self::assertEquals($this->scorePoints, $this->sut->getPoints());
    }

    public function testGetScoreType(): void
    {
        self::assertEquals(ScoreType::RELATIVE_SCORE_TYPE, $this->sut->getType()->getValue());
    }

    public function testGetRelativeOperation(): void
    {
        self::assertEquals($this->scoreOperation, $this->sut->getOperation());
    }
}
