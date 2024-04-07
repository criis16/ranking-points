<?php

namespace Tests\Domain\User;

use App\Domain\User\User;
use App\Domain\User\UserId;
use InvalidArgumentException;
use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\RelativeScore\RelativeScore;

class UserTest extends TestCase
{
    private User $sut;

    /** @var UserId&MockObject */
    private UserId $userId;

    /** @var RelativeScore&MockObject */
    private RelativeScore $userScore;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->userScore = $this->createMock(RelativeScore::class);
        $this->sut = new User(
            $this->userId,
            $this->userScore
        );
    }

    public function testGetUserId(): void
    {
        $this->assertSame($this->userId, $this->sut->getUserId());
    }

    public function testGetScore(): void
    {
        $this->assertSame($this->userScore, $this->sut->getScore());
    }

    public function testInvalidScoreType(): void
    {
        $invalidScoreType = $this->createMock(ScoreType::class);
        $invalidScoreType->expects(self::once())
            ->method('getValue')
            ->willReturn('an invalid score type');

        $invalidScore = $this->createMock(RelativeScore::class);
        $invalidScore->expects(self::once())
            ->method('getType')
            ->willReturn($invalidScoreType);

        $this->expectException(InvalidArgumentException::class);
        $this->sut->addScore($invalidScore);
    }

    public function testAddAbsoluteScore(): void
    {
        $absoluteScoreType = $this->createMock(ScoreType::class);
        $absoluteScoreType->expects(self::once())
            ->method('getValue')
            ->willReturn(ScoreType::ABSOLUTE_SCORE_TYPE);

        $absoluteScore = $this->createMock(AbsoluteScore::class);
        $absoluteScore->expects(self::once())
            ->method('getType')
            ->willReturn($absoluteScoreType);

            $this->userScore->expects(self::once())
            ->method('update')
            ->with($absoluteScore);

        $this->sut->addScore($absoluteScore);
    }

    public function testAddRelativeScore(): void
    {
        $relativeScoreType = $this->createMock(ScoreType::class);
        $relativeScoreType->expects(self::once())
            ->method('getValue')
            ->willReturn(ScoreType::RELATIVE_SCORE_TYPE);

        $relativeScore = $this->createMock(RelativeScore::class);
        $relativeScore->expects(self::once())
            ->method('getType')
            ->willReturn($relativeScoreType);

        $this->userScore->expects(self::once())
            ->method('update')
            ->with($relativeScore);

        $this->sut->addScore($relativeScore);
    }
}
