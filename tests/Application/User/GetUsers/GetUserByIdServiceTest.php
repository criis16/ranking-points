<?php

namespace Tests\Application\User\GetUsers;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\GetUsers\GetUserByIdService;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\UserId;

class GetUserByIdServiceTest extends TestCase
{
    private GetUserByIdService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->sut = new GetUserByIdService(
            $this->repository
        );
    }

    public function testExecute(): void
    {
        $userIdValue = 'user-123';
        $userId = new UserId($userIdValue);

        /** @var User[]&MockObject */
        $user = $this->createMock(User::class);
        $userData = [$user];

        $this->repository
            ->expects($this->once())
            ->method('getUserById')
            ->with($userId)
            ->willReturn($userData);

        $result = $this->sut->execute($userIdValue);

        $this->assertEquals($userData, $result);
    }
}
