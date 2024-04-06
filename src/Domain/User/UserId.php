<?php

namespace App\Domain\User;

class UserId
{
    private string $id;

    public function __construct(string $id)
    {
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
