<?php

namespace Tests\Application\User\GetUsers;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\Adapters\UserAdapter;
use App\Application\User\GetUsers\GetUsersService;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUsersServiceTest extends TestCase
{
    private GetUsersService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    /** @var UserAdapter&MockObject */
    private UserAdapter $userAdapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->userAdapter = $this->createMock(UserAdapter::class);
        $this->sut = new GetUsersService(
            $this->repository,
            $this->userAdapter
        );
    }

    /**
     * @dataProvider getUsersDataProvier
     */
    public function testExecuteWorksCorrectly(
        array $users,
        array $adaptedUser,
        array $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getUsers')
            ->willReturn($users);
        $this->userAdapter->expects(self::exactly(\count($users)))
            ->method('adapt')
            ->with(self::isInstanceOf(User::class))
            ->willReturn($adaptedUser);


        $this->assertEquals($expectedResult, $this->sut->execute());
    }

    public function getUsersDataProvier(): array
    {
        return [
            'simple_case' => self::simpleCase(),
            'multiple_users_case' => self::multipleUsersCase()
        ];
    }

    private function simpleCase(): array
    {
        $adaptedUser = [
            'id' => 'user id',
            'score' => 9999
        ];

        return [
            'users_output' => [
                $this->createMock(User::class)
            ],
            'adapt_output' => $adaptedUser,
            'expected_output' => [
                $adaptedUser
            ]
        ];
    }

    private function multipleUsersCase(): array
    {
        $adaptedUser = [
            'id' => 'user id',
            'score' => 9999
        ];

        return [
            'users_output' => [
                $this->createMock(User::class),
                $this->createMock(User::class)
            ],
            'adapt_output' => $adaptedUser,
            'expected_output' => [
                $adaptedUser,
                $adaptedUser
            ]
        ];
    }
}
