<?php

namespace Tests\Application\User\Adapters;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use App\Application\User\Adapters\UserAdapter;
use App\Domain\Score\Score;
use App\Domain\Score\ScorePoints;
use App\Domain\User\UserId;

class UserAdapterTest extends TestCase
{
    private UserAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new UserAdapter();
    }

    /**
     * @dataProvider adventureAdapterProvider
     */
    public function testAdaptWorksCorrectly(
        User $user,
        array $expectedResult
    ): void {
        $this->assertEquals($expectedResult, $this->sut->adapt($user));
    }

    public function adventureAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private function simpleCase(): array
    {
        $userId = $this->createMock(UserId::class);
        $userScore = $this->createMock(Score::class);
        $userScorePoints = $this->createMock(ScorePoints::class);
        $user = $this->createMock(User::class);

        $userIdString = 'a given user id';
        $userScorePointsNumber = 1234;

        $userId->expects(self::once())
            ->method('getValue')
            ->willReturn($userIdString);

        $userScorePoints->expects(self::once())
            ->method('getValue')
            ->willReturn($userScorePointsNumber);

        $userScore->expects(self::once())
            ->method('getPoints')
            ->willReturn($userScorePoints);

        $user->expects(self::once())
            ->method('getUserId')
            ->willReturn($userId);

        $user->expects(self::once())
            ->method('getScore')
            ->willReturn($userScore);

        return [
            'user_input' => $user,
            'expected_output' => [
                'id' => $userIdString,
                'score' => $userScorePointsNumber
            ]
        ];
    }
}
