<?php

namespace Tests\Domain\Score\RelativeScore;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Score\RelativeScore\RelativeScoreOperation;

class RelativeScoreOperationTest extends TestCase
{
    public function testEqualsWorksCorrectly(): void
    {
        self::assertTrue(
            RelativeScoreOperation::instance(RelativeScoreOperation::ADD_OPERATION)->equals(
                RelativeScoreOperation::instance(RelativeScoreOperation::ADD_OPERATION)
            )
        );

        self::assertFalse(
            RelativeScoreOperation::instance(RelativeScoreOperation::ADD_OPERATION)->equals(
                RelativeScoreOperation::instance(RelativeScoreOperation::SUBSTRACT_OPERATION)
            )
        );
    }

    public function testInstanceWorksCorrectly(): void
    {
        self::assertSame(
            RelativeScoreOperation::ADD_OPERATION,
            RelativeScoreOperation::instance(RelativeScoreOperation::ADD_OPERATION)->getValue()
        );

        self::assertSame(
            RelativeScoreOperation::instance(RelativeScoreOperation::SUBSTRACT_OPERATION),
            RelativeScoreOperation::instance(RelativeScoreOperation::SUBSTRACT_OPERATION)
        );
    }

    public function testInstanceThrowsExceptionWhenInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Relative score operation not allowed: an invalid type');
        RelativeScoreOperation::instance('an invalid type');
    }
}
