<?php

namespace Tests\Domain\User;

use App\Domain\User\User;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Score\RelativeScore\RelativeScore;

class UserTest extends TestCase
{
    private User $sut;

    /** @var UserId&MockObject */
    private UserId $userId;

    /** @var RelativeScore&MockObject */
    private RelativeScore $userScore;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->userScore = $this->createMock(RelativeScore::class);
        $this->sut = new User(
            $this->userId,
            $this->userScore
        );
    }

    public function testGetUserId(): void
    {
        $this->assertSame($this->userId, $this->sut->getUserId());
    }

    public function testGetScore(): void
    {
        $this->assertSame($this->userScore, $this->sut->getScore());
    }
}
