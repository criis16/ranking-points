<?php

namespace Tests\Application\User\AddUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\AddUser\AddUserService;
use App\Application\User\GetUsers\GetUserByIdService;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;
use App\Infrastructure\User\Repositories\AddUserRequest;
use App\Domain\User\Repositories\UserRepositoryInterface;

class AddUserServiceTest extends TestCase
{
    private AddUserService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    /** @var GetUserByIdService&MockObject */
    private GetUserByIdService $getUserByIdService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->getUserByIdService = $this->createMock(GetUserByIdService::class);
        $this->sut = new AddUserService(
            $this->repository,
            $this->getUserByIdService
        );
    }

    public function testExecuteAddsNewUserWithAbsoluteScore(): void
    {
        $userIdValue = 'user id';
        $totalScore = 150;

        /** @var AddUserRequest&MockObject */
        $request = $this->createMock(AddUserRequest::class);
        $request->expects($this->once())
            ->method('getId')
            ->willReturn($userIdValue);
        $request->expects($this->once())
            ->method('getTotalScore')
            ->willReturn($totalScore);
        $request->expects($this->never())
            ->method('getRelativeScore');
        $request->expects($this->never())
            ->method('getOperation');

        $this->getUserByIdService->expects($this->once())
            ->method('execute')
            ->with($userIdValue)
            ->willReturn([]);

        $user = User::create(new UserId($userIdValue));
        $user->setAbsoluteScore(new ScorePoints($totalScore));
        $this->repository->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $this->sut->execute($request);
    }

    public function testExecuteAddsExistingUserWithAbsoluteScore(): void
    {
        $userIdValue = 'user id';
        $totalScore = 150;

        /** @var AddUserRequest&MockObject */
        $request = $this->createMock(AddUserRequest::class);
        $request->expects($this->once())
            ->method('getId')
            ->willReturn($userIdValue);
        $request->expects($this->once())
            ->method('getTotalScore')
            ->willReturn($totalScore);
        $request->expects($this->never())
            ->method('getRelativeScore');
        $request->expects($this->never())
            ->method('getOperation');

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('setAbsoluteScore')
            ->with(new ScorePoints($totalScore));
        $this->getUserByIdService->expects($this->once())
            ->method('execute')
            ->with($userIdValue)
            ->willReturn([$user]);

        $this->repository->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $this->sut->execute($request);
    }

    public function testExecuteAddsNewUserWithRelativeScore(): void
    {
        $userIdValue = 'user id';
        $relativeScore = 150;
        $operation = '+';

        /** @var AddUserRequest&MockObject */
        $request = $this->createMock(AddUserRequest::class);
        $request->expects($this->once())
            ->method('getId')
            ->willReturn($userIdValue);
        $request->expects($this->once())
            ->method('getTotalScore')
            ->willReturn(null);
        $request->expects($this->once())
            ->method('getRelativeScore')
            ->willReturn($relativeScore);
        $request->expects($this->once())
            ->method('getOperation')
            ->willReturn($operation);

        $this->getUserByIdService->expects($this->once())
            ->method('execute')
            ->with($userIdValue)
            ->willReturn([]);

        $user = User::create(new UserId($userIdValue));
        $user->setRelativeScore(
            new ScorePoints($relativeScore),
            RelativeScoreOperation::instance($operation)
        );
        $this->repository->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $this->sut->execute($request);
    }

    public function testExecuteAddsExistingUserWithRelativeScore(): void
    {
        $userIdValue = 'user id';
        $relativeScore = 150;
        $operation = '+';

        /** @var AddUserRequest&MockObject */
        $request = $this->createMock(AddUserRequest::class);
        $request->expects($this->once())
            ->method('getId')
            ->willReturn($userIdValue);
        $request->expects($this->once())
            ->method('getTotalScore')
            ->willReturn(null);
        $request->expects($this->once())
            ->method('getRelativeScore')
            ->willReturn($relativeScore);
        $request->expects($this->once())
            ->method('getOperation')
            ->willReturn($operation);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('setRelativeScore')
            ->with(
                new ScorePoints($relativeScore),
                RelativeScoreOperation::instance($operation)
            );
        $this->getUserByIdService->expects($this->once())
            ->method('execute')
            ->with($userIdValue)
            ->willReturn([$user]);

        $this->repository->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $this->sut->execute($request);
    }
}
