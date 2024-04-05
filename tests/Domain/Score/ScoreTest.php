<?php

namespace Tests\Domain\Score;

use InvalidArgumentException;
use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

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

    public function testUpdateThrowsExceptionForInvalidOperation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $newScorePoints = 50;

        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);
        $scorePoints->expects(self::once())
            ->method('getValue')
            ->willReturn(
                $newScorePoints
            );

        /** @var RelativeScoreOperation&MockObject */
        $relativeScoreOperation = $this->createMock(RelativeScoreOperation::class);
        $relativeScoreOperation->expects(self::once())
            ->method('getValue')
            ->willReturn('an invalid operation');

        /** @var RelativeScore&MockObject */
        $relativeScore = $this->createMock(RelativeScore::class);
        $relativeScore->expects(self::once())
            ->method('getPoints')
            ->willReturn($scorePoints);

        $relativeScore->expects(self::once())
            ->method('getOperation')
            ->willReturn($relativeScoreOperation);

        $this->sut->update($relativeScore);
    }

    /**
     * @dataProvider scoreUpdateProvider
     */
    public function testUpdate(
        int $userScorePoints,
        RelativeScore $relativeScore,
        int $expectedPoints
    ): void {
        $this->points->expects(self::once())
            ->method('getValue')
            ->willReturn($userScorePoints);

        $this->sut->update($relativeScore);
        $this->assertEquals($expectedPoints, $this->sut->getPoints()->getValue());
    }

    public function scoreUpdateProvider(): array
    {
        return [
            'add_case' => self::addCase(),
            'substract_simple_case' => self::substractSimpleCase(),
            'substract_negative_case' => self::substractNegativeCase()
        ];
    }

    private function addCase(): array
    {
        $userScorePoints = 100;
        $newScorePoints = 50;

        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);
        $scorePoints->expects(self::once())
            ->method('getValue')
            ->willReturn(
                $newScorePoints
            );

        /** @var RelativeScoreOperation&MockObject */
        $relativeScoreOperation = $this->createMock(RelativeScoreOperation::class);
        $relativeScoreOperation->expects(self::once())
            ->method('getValue')
            ->willReturn(RelativeScoreOperation::ADD_OPERATION);

        /** @var RelativeScore&MockObject */
        $relativeScore = $this->createMock(RelativeScore::class);
        $relativeScore->expects(self::once())
            ->method('getPoints')
            ->willReturn($scorePoints);

        $relativeScore->expects(self::once())
            ->method('getOperation')
            ->willReturn($relativeScoreOperation);

        return [
            'user_score_points' => $userScorePoints,
            'relative_score_input' => $relativeScore,
            'expected_points' => $userScorePoints + $newScorePoints
        ];
    }

    private function substractSimpleCase(): array
    {
        $userScorePoints = 50;
        $newScorePoints = 25;

        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);
        $scorePoints->expects(self::once())
            ->method('getValue')
            ->willReturn(
                $newScorePoints
            );

        /** @var RelativeScoreOperation&MockObject */
        $relativeScoreOperation = $this->createMock(RelativeScoreOperation::class);
        $relativeScoreOperation->expects(self::once())
            ->method('getValue')
            ->willReturn(RelativeScoreOperation::SUBSTRACT_OPERATION);

        /** @var RelativeScore&MockObject */
        $relativeScore = $this->createMock(RelativeScore::class);
        $relativeScore->expects(self::once())
            ->method('getPoints')
            ->willReturn($scorePoints);

        $relativeScore->expects(self::once())
            ->method('getOperation')
            ->willReturn($relativeScoreOperation);

        return [
            'user_score_points' => $userScorePoints,
            'relative_score_input' => $relativeScore,
            'expected_points' => $userScorePoints - $newScorePoints
        ];
    }

    private function substractNegativeCase(): array
    {
        $userScorePoints = 0;
        $newScorePoints = 1;

        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);
        $scorePoints->expects(self::once())
            ->method('getValue')
            ->willReturn(
                $newScorePoints
            );

        /** @var RelativeScoreOperation&MockObject */
        $relativeScoreOperation = $this->createMock(RelativeScoreOperation::class);
        $relativeScoreOperation->expects(self::once())
            ->method('getValue')
            ->willReturn(RelativeScoreOperation::SUBSTRACT_OPERATION);

        /** @var RelativeScore&MockObject */
        $relativeScore = $this->createMock(RelativeScore::class);
        $relativeScore->expects(self::once())
            ->method('getPoints')
            ->willReturn($scorePoints);

        $relativeScore->expects(self::once())
            ->method('getOperation')
            ->willReturn($relativeScoreOperation);

        return [
            'user_score_points' => $userScorePoints,
            'relative_score_input' => $relativeScore,
            'expected_points' => ScorePoints::ZERO_POINTS
        ];
    }
}
