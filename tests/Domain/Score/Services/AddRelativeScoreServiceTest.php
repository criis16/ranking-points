<?php

namespace Tests\Domain\Score\Services;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\RelativeScore\RelativeScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;
use App\Domain\Score\Services\AddRelativeScoreService;

class AddRelativeScoreServiceTest extends TestCase
{
    private AddRelativeScoreService $sut;

    protected function setUp(): void
    {
        $this->sut = new AddRelativeScoreService();
    }

    /**
     * @dataProvider addRelativeScoreProvider
     */
    public function testExecuteWorksCorrectly(
        User $user,
        ScorePoints $scorePoints,
        RelativeScoreOperation $relativeScoreOperation
    ): void {
        $this->sut->execute($user, $scorePoints, $relativeScoreOperation);
    }

    public function addRelativeScoreProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private function simpleCase(): array
    {
        /** @var ScorePoints&MockObject */
        $scorePoints = $this->createMock(ScorePoints::class);

        /** @var RelativeScoreOperation&MockObject */
        $relativeScoreOperation = $this->createMock(RelativeScoreOperation::class);

        $relativeScore = new RelativeScore($scorePoints);
        $relativeScore->setOperation($relativeScoreOperation);

        /** @var User&MockObject */
        $user = $this->createMock(User::class);
        $user->expects(self::once())
            ->method('addScore')
            ->with($relativeScore);

        return [
            'user_input' => $user,
            'score_points_input' => $scorePoints,
            'relative_score_operation_input' => $relativeScoreOperation
        ];
    }
}
