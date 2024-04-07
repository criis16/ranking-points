<?php

namespace Tests\Application\User\Ranking;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\Adapters\UserAdapter;
use App\Application\User\Ranking\GetRankedUsersService;
use App\Application\User\Ranking\GetAtRankedUsersService;
use App\Application\User\Ranking\GetTopRankedUsersService;

class GetRankedUsersServiceTest extends TestCase
{
    private GetRankedUsersService $sut;

    /** @var GetTopRankedUsersService&MockObject */
    private GetTopRankedUsersService $getTopRankedUsersService;

    /** @var GetAtRankedUsersService&MockObject */
    private GetAtRankedUsersService $getAtRankedUsersService;

    /** @var UserAdapter&MockObject */
    private UserAdapter $userAdapter;

    protected function setUp(): void
    {
        $this->userAdapter = $this->createMock(UserAdapter::class);
        $this->getTopRankedUsersService = $this->createMock(GetTopRankedUsersService::class);
        $this->getAtRankedUsersService = $this->createMock(GetAtRankedUsersService::class);
        $this->sut = new GetRankedUsersService(
            $this->getTopRankedUsersService,
            $this->getAtRankedUsersService,
            $this->userAdapter
        );
    }

    /**
     * @dataProvider topRankingDataProvier
     */
    public function testTopRankingExecuteWorksCorrectly(
        string $rankingType,
        int $topNumber,
        array $users,
        array $adaptedUser,
        array $expectedResult
    ): void {
        $this->mockTopRankedUsersService($topNumber, $users);
        $this->mockUserAdapter($users, $adaptedUser);
        $this->assertEquals($expectedResult, $this->sut->execute($rankingType, $topNumber));
    }

    /**
     * @dataProvider atRankingDataProvier
     */
    public function testAtRankingExecuteWorksCorrectly(
        string $rankingType,
        int $firstNumber,
        int $secondNumber,
        array $users,
        array $adaptedUser,
        array $expectedResult
    ): void {
        $this->mockAtRankedUsersService($firstNumber, $secondNumber, $users);
        $this->mockUserAdapter($users, $adaptedUser);
        $this->assertEquals(
            $expectedResult,
            $this->sut->execute($rankingType, $firstNumber, $secondNumber)
        );
    }

    public function topRankingDataProvier(): array
    {
        return [
            'top_simple_case' => [
                'ranking_type_input' => 'top',
                'top_number_input' => 1,
                'users_output' => [
                    $this->createMock(User::class),
                ],
                'adapt_output' => ['id' => 'a user id', 'score' => 9999],
                'expected_output' => [['id' => 'a user id', 'score' => 9999]]
            ],
            'top_multiple_users_case' => [
                'ranking_type_input' => 'top',
                'top_number_input' => 1,
                'users_output' => [
                    $this->createMock(User::class),
                    $this->createMock(User::class)
                ],
                'adapt_output' => ['id' => 'a user id', 'score' => 9999],
                'expected_output' => [
                    ['id' => 'a user id', 'score' => 9999],
                    ['id' => 'a user id', 'score' => 9999]
                ]
            ]
        ];
    }

    public function atRankingDataProvier(): array
    {
        return [
            'at_simple_case' => [
                'ranking_type_input' => 'at',
                'first_number_input' => 1,
                'second_number_input' => 1,
                'users_output' => [
                    $this->createMock(User::class),
                    $this->createMock(User::class)
                ],
                'adapt_output' => ['id' => 'a user id', 'score' => 9999],
                'expected_output' => [
                    ['id' => 'a user id', 'score' => 9999],
                    ['id' => 'a user id', 'score' => 9999]
                ],
            ],
            'at_multiple_users_case' => [
                'ranking_type_input' => 'at',
                'first_number_input' => 3,
                'second_number_input' => 1,
                'users_output' => [
                    $this->createMock(User::class),
                    $this->createMock(User::class),
                    $this->createMock(User::class)
                ],
                'adapt_output' => ['id' => 'a user id', 'score' => 9999],
                'expected_output' => [
                    ['id' => 'a user id', 'score' => 9999],
                    ['id' => 'a user id', 'score' => 9999],
                    ['id' => 'a user id', 'score' => 9999]
                ]
            ]
        ];
    }

    private function mockTopRankedUsersService(int $topNumber, array $users): void
    {
        $this->getTopRankedUsersService->expects(self::once())
            ->method('execute')
            ->with($topNumber)
            ->willReturn($users);
    }

    private function mockAtRankedUsersService(int $firstNumber, int $secondNumber, array $users): void
    {
        $this->getAtRankedUsersService->expects(self::once())
            ->method('execute')
            ->with($firstNumber, $secondNumber)
            ->willReturn($users);
    }

    private function mockUserAdapter(array $users, array $adaptedUser): void
    {
        $this->userAdapter->expects(self::exactly(\count($users)))
            ->method('adapt')
            ->with(self::isInstanceOf(User::class))
            ->willReturn($adaptedUser);
    }
}
