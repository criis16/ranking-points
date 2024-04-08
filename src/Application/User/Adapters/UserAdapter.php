<?php

namespace App\Application\User\Adapters;

use App\Domain\User\User;

class UserAdapter
{
    /**
     * Adapts a given User domain object into an array
     *
     * @param User $user
     * @return array
     */
    public function adapt(
        User $user
    ): array {
        return [
            'id' => $user->getUserId()->getValue(),
            'score' => $user->getScore()->getPoints()->getValue()
        ];
    }
}
