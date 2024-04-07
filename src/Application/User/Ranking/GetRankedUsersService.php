<?php

namespace App\Application\User\Ranking;

use App\Domain\User\User;
use App\Application\User\Adapters\UserAdapter;
use App\Infrastructure\User\GetRankedUsersController;
use App\Application\User\Ranking\GetAtRankedUsersService;

class GetRankedUsersService
{
    private GetTopRankedUsersService $getTopRankedUsersService;
    private GetAtRankedUsersService $getAtRankedUsersService;
    private UserAdapter $adapter;

    public function __construct(
        GetTopRankedUsersService $getTopRankedUsersService,
        GetAtRankedUsersService $getAtRankedUsersService,
        UserAdapter $adapter
    ) {
        $this->getTopRankedUsersService = $getTopRankedUsersService;
        $this->getAtRankedUsersService = $getAtRankedUsersService;
        $this->adapter = $adapter;
    }


    public function execute(
        string $rankingType,
        int $firstNumber,
        ?int $secondNumber = null,
    ): array {
        if ($rankingType === GetRankedUsersController::TOP_RANKING) {
            $users = $this->getTopRankedUsersService->execute($firstNumber);
        } else {
            $users = $this->getAtRankedUsersService->execute($firstNumber, $secondNumber);
        }

        return \array_map(
            function (User $user) {
                return $this->adapter->adapt($user);
            },
            \array_values($users)
        );
    }
}
