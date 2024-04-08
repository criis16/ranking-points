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
        $isSaved = false;
        $statusCode = 400;

        try {
            $requestBody = $request->request->all();
            $this->validateRequestDataService->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData(
                $userId,
                $requestBody
            );

            $isSaved = $this->addUserService->execute($this->addUserRequest);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        if ($isSaved) {
            $responseMessage = 'The user\'s score with id ' . $userId . ' has been successfully saved';
            $statusCode = 200;
        }

        return new JsonResponse(
            [
                'status_code' => $statusCode,
                'message' => $responseMessage,
                'result' => \intval($isSaved),
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

        if (\array_key_exists('total', $requestData)) {
            $this->addUserRequest->setTotalScore($requestData['total']);
        }

        if (\array_key_exists('score', $requestData)) {
            $scoreData = $this->extractRelativeScore($requestData['score']);

            if (empty($scoreData)) {
                throw new InvalidArgumentException('Invalid score.');
            }

            $this->addUserRequest->setOperation($scoreData['operation']);
            $this->addUserRequest->setRelativeScore($scoreData['value']);
        }
    }

    private function extractRelativeScore(string $relativeScore): array
    {
        $result = [];

        if (preg_match('/^(^[\+\-\*\/])(\d+)$/is', $relativeScore, $matches)) {
            $result = [
                'operation' => $matches[1],
                'value' => \intval($matches[2])
            ];
        }

        return $result;
    }
}
