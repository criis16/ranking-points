<?php

namespace App\Application\User\Adapters;

use App\Domain\User\User;

class UserAdapter
{
    public function adapt(
        User $user
    ): array {
        return [
            'id' => $user->getUserId()->getValue(),
            'score' => $user->getScore()->getPoints()->getValue()
        ];
    }
}
