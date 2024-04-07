<?php

namespace Tests\Application\User\Ranking;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Application\User\Ranking\GetTopRankedUsersService;

class GetTopRankedUsersServiceTest extends TestCase
{
    private GetTopRankedUsersService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->sut = new GetTopRankedUsersService($this->repository);
    }

    /**
     * @dataProvider topRankingDataProvier
     */
    public function testExecuteWorksCorrectly(
        int $topNumber,
        array $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getTopUsers')
            ->with($topNumber)
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->sut->execute($topNumber));
    }

    public function topRankingDataProvier(): array
    {
        return [
            'top_simple_case' => [
                'top_number_input' => 1,
                'expected_output' => [
                    'user on first position'
                ]
            ],
            'top_multiple_users_case' => [
                'top_number_input' => 2,
                'expected_output' => [
                    'user on first position',
                    'user on second position'
                ]
            ]
        ];
    }
}
