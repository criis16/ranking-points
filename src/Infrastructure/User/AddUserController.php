<?php

namespace App\Infrastructure\User;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Application\User\AddUser\AddUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Shared\ValidateRequestDataService;
use App\Infrastructure\User\Repositories\AddUserRequest;
use InvalidArgumentException;

class AddUserController
{
    private const REQUIRED_BODY_FIELDS = [
        'total',
        'score',
    ];

    private AddUserService $addUserService;
    private AddUserRequest $addUserRequest;
    private ValidateRequestDataService $validateRequestDataService;

    public function __construct(
        AddUserService $addUserService,
        AddUserRequest $addUserRequest,
        ValidateRequestDataService $validateRequestDataService
    ) {
        $this->addUserService = $addUserService;
        $this->addUserRequest = $addUserRequest;
        $this->validateRequestDataService = $validateRequestDataService;
    }

    /**
     * Method that adds a score to a given user id
     *
     * @param Request $request
     * @param string $userId
     * @return JsonResponse
     */
    public function add(Request $request, string $userId): JsonResponse
    {
        try {
            $requestBody = $request->request->all();
            $this->validateRequestDataService->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData($userId, $requestBody);
            $this->addUserService->execute($this->addUserRequest);
            $responseMessage = 'The user\'s score has been successfully saved';
            $statusCode = JsonResponse::HTTP_OK;
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
            $statusCode = JsonResponse::HTTP_BAD_REQUEST;
        }

        return new JsonResponse(
            [
                'status_code' => $statusCode,
                'message' => $responseMessage,
            ],
            $statusCode
        );
    }

    /**
     * Set request data
     *
     * @param array $requestData
     * @return void
     * @throws InvalidArgumentException
     */
    private function setRequestData(
        string $userId,
        array $requestData
    ): void {
        $this->addUserRequest->setId($userId);
        $this->addUserRequest->setScore($requestData);
    }
}
