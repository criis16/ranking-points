<?php

namespace App\Domain\User;

class UserId
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Return the user id value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->id;
    }
}
