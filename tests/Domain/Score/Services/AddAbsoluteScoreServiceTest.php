<?php

namespace Tests\Domain\Score\Services;

use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\Services\AddAbsoluteScoreService;
use App\Domain\User\User;

class AddAbsoluteScoreServiceTest extends TestCase
{
    private AddAbsoluteScoreService $sut;

    protected function setUp(): void
    {
        $this->sut = new AddAbsoluteScoreService();
    }

    /**
     * @dataProvider addAbsoluteScoreProvider
     */
    public function testExecuteWorksCorrectly(
        User $user,
        ScorePoints $scorePoints
    ): void {
        $this->sut->execute($user, $scorePoints);
    }

    public function addAbsoluteScoreProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private function simpleCase(): array
    {
        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);

        $absoluteScore = new AbsoluteScore($scorePoints);

        /** @var User&MockObject */
        $user = $this->createMock(User::class);
        $user->expects(self::once())
            ->method('addScore')
            ->with($absoluteScore);

        return [
            'user_input' => $user,
            'score_points_input' => $scorePoints
        ];
    }
}
