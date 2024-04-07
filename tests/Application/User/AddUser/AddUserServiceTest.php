<?php

namespace Tests\Application\User\AddUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\ScorePoints;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\User\AddUser\AddUserService;
use App\Domain\Score\AbsoluteScore\AbsoluteScore;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;
use App\Domain\Score\Services\AddAbsoluteScoreService;
use App\Domain\Score\Services\AddRelativeScoreService;
use App\Infrastructure\User\Repositories\AddUserRequest;
use App\Domain\User\Repositories\UserRepositoryInterface;

class AddUserServiceTest extends TestCase
{
    private AddUserService $sut;

    /** @var UserRepositoryInterface&MockObject */
    private UserRepositoryInterface $repository;

    /** @var AddRelativeScoreService&MockObject */
    private AddRelativeScoreService $addRelativeScoreService;

    /** @var AddAbsoluteScoreService&MockObject */
    private AddAbsoluteScoreService $addAbsoluteScoreService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->addRelativeScoreService = $this->createMock(AddRelativeScoreService::class);
        $this->addAbsoluteScoreService = $this->createMock(AddAbsoluteScoreService::class);
        $this->sut = new AddUserService(
            $this->repository,
            $this->addRelativeScoreService,
            $this->addAbsoluteScoreService
        );
    }

    /**
     * @dataProvider absoluteScoreDataProvier
     */
    public function testAbsoluteScoreExecuteWorksCorrectly(
        AddUserRequest $addUserRequest,
        User $user,
        UserId $userId,
        ScorePoints $totalScorePoints,
        array $users,
        bool $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getUserById')
            ->with($userId)
            ->willReturn($users);
        $this->repository->expects(self::once())
            ->method('saveUser')
            ->with($user)
            ->willReturn(true);

        $this->addAbsoluteScoreService->expects(self::once())
            ->method('execute')
            ->with($user, $totalScorePoints)
            ->willReturn($user);

        $this->assertEquals($expectedResult, $this->sut->execute($addUserRequest));
    }

    /**
     * @dataProvider relativeScoreDataProvider
     */
    public function testRelativeScoreExecuteWorksCorrectly(
        AddUserRequest $addUserRequest,
        User $user,
        UserId $userId,
        ScorePoints $totalScorePoints,
        RelativeScoreOperation $relativeScoreOperation,
        array $users,
        bool $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getUserById')
            ->with($userId)
            ->willReturn($users);
        $this->repository->expects(self::once())
            ->method('saveUser')
            ->with($user)
            ->willReturn(true);

        $this->addRelativeScoreService->expects(self::once())
            ->method('execute')
            ->with($user, $totalScorePoints, $relativeScoreOperation)
            ->willReturn($user);

        $this->assertEquals($expectedResult, $this->sut->execute($addUserRequest));
    }

    public function absoluteScoreDataProvier(): array
    {
        return [
            'new_user_absolute_score_case' => self::newUserAbsoluteScoreCase(),
            'existing_user_absolute_score_case' => self::existingUserAbsoluteScoreCase()
        ];
    }

    public function relativeScoreDataProvider(): array
    {
        return [
            'new_user_relative_score_case' => self::newUserRelativeScoreCase(),
            'existing_user_relative_score_case' => self::existingUserRelativeScoreCase()
        ];
    }

    private function newUserAbsoluteScoreCase(): array
    {
        $totalScore = 9999;
        $userIdString = 'a user id';
        $userId = new UserId($userIdString);
        $emptyScorePoints = new ScorePoints(AddUserService::EMPTY_SCORE);
        $totalScorePoints = new ScorePoints($totalScore);
        $absoluteScore = new AbsoluteScore($emptyScorePoints);
        $user = new User($userId, $absoluteScore);

        $addUserRequest = $this->createMock(AddUserRequest::class);
        $addUserRequest->expects(self::once())
            ->method('getId')
            ->willReturn($userIdString);
        $addUserRequest->expects(self::once())
            ->method('getTotalScore')
            ->willReturn($totalScore);

        return [
            'request_input' => $addUserRequest,
            'user_input' => $user,
            'user_id_input' => $userId,
            'score_points_input' => $totalScorePoints,
            'check_user_output' => [],
            'expected_output' => true
        ];
    }

    private function existingUserAbsoluteScoreCase(): array
    {
        $totalScore = 9999;
        $userIdString = 'a user id';
        $userId = new UserId($userIdString);
        $totalScorePoints = new ScorePoints($totalScore);
        $user = $this->createMock(User::class);

        $addUserRequest = $this->createMock(AddUserRequest::class);
        $addUserRequest->expects(self::once())
            ->method('getId')
            ->willReturn($userIdString);
        $addUserRequest->expects(self::once())
            ->method('getTotalScore')
            ->willReturn($totalScore);

        return [
            'request_input' => $addUserRequest,
            'user_input' => $user,
            'user_id_input' => $userId,
            'score_points_input' => $totalScorePoints,
            'check_user_output' => [$user],
            'expected_output' => true
        ];
    }

    private function newUserRelativeScoreCase(): array
    {
        $totalScore = null;
        $relativeScore = 20;
        $operationString = '+';
        $userIdString = 'a user id';
        $userId = new UserId($userIdString);
        $emptyScorePoints = new ScorePoints(AddUserService::EMPTY_SCORE);
        $absoluteScore = new AbsoluteScore($emptyScorePoints);
        $relativeScorePoints = new ScorePoints($relativeScore);
        $user = new User($userId, $absoluteScore);
        $relativeScoreOperation = RelativeScoreOperation::instance($operationString);

        $addUserRequest = $this->createMock(AddUserRequest::class);
        $addUserRequest->expects(self::once())
            ->method('getId')
            ->willReturn($userIdString);
        $addUserRequest->expects(self::once())
            ->method('getTotalScore')
            ->willReturn($totalScore);
        $addUserRequest->expects(self::once())
            ->method('getRelativeScore')
            ->willReturn($relativeScore);
        $addUserRequest->expects(self::once())
            ->method('getOperation')
            ->willReturn($operationString);

        return [
            'request_input' => $addUserRequest,
            'user_input' => $user,
            'user_id_input' => $userId,
            'score_points_input' => $relativeScorePoints,
            'operation_input' => $relativeScoreOperation,
            'check_user_output' => [],
            'expected_output' => true
        ];
    }

    private function existingUserRelativeScoreCase(): array
    {
        $totalScore = null;
        $relativeScore = 20;
        $operationString = '+';
        $userIdString = 'a user id';
        $userId = new UserId($userIdString);
        $relativeScorePoints = new ScorePoints($relativeScore);
        $relativeScoreOperation = RelativeScoreOperation::instance($operationString);
        $user = $this->createMock(User::class);

        $addUserRequest = $this->createMock(AddUserRequest::class);
        $addUserRequest->expects(self::once())
            ->method('getId')
            ->willReturn($userIdString);
        $addUserRequest->expects(self::once())
            ->method('getTotalScore')
            ->willReturn($totalScore);
        $addUserRequest->expects(self::once())
            ->method('getRelativeScore')
            ->willReturn($relativeScore);
        $addUserRequest->expects(self::once())
            ->method('getOperation')
            ->willReturn($operationString);

        return [
            'request_input' => $addUserRequest,
            'user_input' => $user,
            'user_id_input' => $userId,
            'score_points_input' => $relativeScorePoints,
            'operation_input' => $relativeScoreOperation,
            'check_user_output' => [$user],
            'expected_output' => true
        ];
    }
}
