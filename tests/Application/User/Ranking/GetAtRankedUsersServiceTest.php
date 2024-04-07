<?php

namespace Tests\Application\User\Ranking;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\Ranking\GetAtRankedUsersService;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetAtRankedUsersServiceTest extends TestCase
{
    private GetAtRankedUsersService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->sut = new GetAtRankedUsersService($this->repository);
    }

    /**
     * @dataProvider atRankingDataProvier
     */
    public function testExecuteWorksCorrectly(
        int $position,
        int $range,
        array $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getUserByRange')
            ->with($position - 1, $range)
            ->willReturn($expectedResult);

        $this->assertEquals(
            $expectedResult,
            $this->sut->execute($position, $range)
        );
    }

    public function atRankingDataProvier(): array
    {
        return [
            'at_simple_case' => [
                'position_number_input' => 1,
                'range_number_input' => 1,
                'expected_output' => [
                    'user on first position',
                    'user on second position'

                ]
            ],
            'at_multiple_users_case' => [
                'position_number_input' => 3,
                'range_number_input' => 1,
                'expected_output' => [
                    'user on second position',
                    'user on third position',
                    'user on fourth position'

                ]
            ]
        ];
    }
}
