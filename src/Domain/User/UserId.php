<?php

namespace App\Domain\User;

use InvalidArgumentException;

class UserId
{
    private string $id;

    public function __construct(string $id)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('User id cannot be empty');
        }

        $this->id = $id;
    }

    /**
     * Return the user id value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->id;
    }
}
