<?php

namespace Tests\Domain\Score;

use InvalidArgumentException;
use App\Domain\Score\ScoreType;
use PHPUnit\Framework\TestCase;

class ScoreTypeTest extends TestCase
{
    public function testEqualsWorksCorrectly(): void
    {
        self::assertTrue(
            ScoreType::instance(ScoreType::ABSOLUTE_SCORE_TYPE)->equals(
                ScoreType::instance(ScoreType::ABSOLUTE_SCORE_TYPE)
            )
        );

        self::assertFalse(
            ScoreType::instance(ScoreType::ABSOLUTE_SCORE_TYPE)->equals(
                ScoreType::instance(ScoreType::RELATIVE_SCORE_TYPE)
            )
        );
    }

    public function testInstanceWorksCorrectly(): void
    {
        self::assertSame(
            ScoreType::ABSOLUTE_SCORE_TYPE,
            ScoreType::instance(ScoreType::ABSOLUTE_SCORE_TYPE)->getValue()
        );

        self::assertSame(
            ScoreType::instance(ScoreType::RELATIVE_SCORE_TYPE),
            ScoreType::instance(ScoreType::RELATIVE_SCORE_TYPE)
        );
    }

    public function testInstanceThrowsExceptionWhenInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Score type not allowed: an invalid type');
        ScoreType::instance('an invalid type');
    }
}
