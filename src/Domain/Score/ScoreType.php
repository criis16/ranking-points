<?php

namespace App\Domain\Score;

use InvalidArgumentException;

class ScoreType
{
    public const ABSOLUTE_SCORE_TYPE = 'absolute_score';
    public const RELATIVE_SCORE_TYPE = 'relative_score';

    private const ALLOWED_TYPES = [
        self::ABSOLUTE_SCORE_TYPE,
        self::RELATIVE_SCORE_TYPE
    ];

    private string $type;

    /** @var ScoreType[] */
    private static array $instances = [];

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Instance method
     *
     * @param string $type
     * @return ScoreType
     * @throws InvalidArgumentException
     */
    public static function instance(string $type): ScoreType
    {
        if (!\in_array($type, self::ALLOWED_TYPES)) {
            throw new InvalidArgumentException('Score type not allowed: ' . $type);
        }

        if (!\array_key_exists($type, self::$instances)) {
            self::$instances[$type] = new self($type);
        }

        return self::$instances[$type];
    }

    /**
     * Return the score type value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->type;
    }

    /**
     * Check if are the same type
     *
     * @param ScoreType $scoreType
     * @return boolean
     */
    public function equals(ScoreType $scoreType): bool
    {
        return $this->type === $scoreType->getValue();
    }
}
