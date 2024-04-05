<?php

namespace App\Domain\Score\RelativeScore;

use InvalidArgumentException;

class RelativeScoreOperation
{
    public const ADD_OPERATION = 'add';
    public const SUBSTRACT_OPERATION = 'substract';

    private const ALLOWED_OPERATIONS = [
        self::ADD_OPERATION,
        self::SUBSTRACT_OPERATION
    ];

    private string $operation;

    /** @var RelativeScoreOperation[] */
    private static array $instances = [];

    private function __construct(string $operation)
    {
        $this->operation = $operation;
    }

    public static function instance(string $operation): RelativeScoreOperation
    {
        if (!\in_array($operation, self::ALLOWED_OPERATIONS)) {
            throw new InvalidArgumentException('Relative score operation not allowed: ' . $operation);
        }

        if (!\array_key_exists($operation, self::$instances)) {
            self::$instances[$operation] = new self($operation);
        }

        return self::$instances[$operation];
    }

    /**
     * Return the score operation value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->operation;
    }

    /**
     * Check if are the same type
     *
     * @param RelativeScoreOperation $operation
     * @return boolean
     */
    public function equals(RelativeScoreOperation $operation): bool
    {
        return $this->operation === $operation->getValue();
    }
}
