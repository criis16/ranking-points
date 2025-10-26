<?php

namespace App\Infrastructure\User;

use Exception;
use App\Application\User\GetUsers\GetUsersService;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUsersController
{
    private GetUsersService $getUsersService;

    public function __construct(
        GetUsersService $getUsersService
    ) {
        $this->getUsersService = $getUsersService;
    }

    /**
     * Method that gets all users
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $response = [];
        $responseMessage = 'No users found';
        $statusCode = JsonResponse::HTTP_NOT_FOUND;

        try {
            $response = $this->getUsersService->execute();
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        if (!empty($response)) {
            $responseMessage = 'Users found successfully';
            $statusCode = JsonResponse::HTTP_OK;
        }

        return new JsonResponse(
            [
                'status_code' => $statusCode,
                'message' => $responseMessage,
                'result' => $response,
            ],
            $statusCode
        );
    }
}
