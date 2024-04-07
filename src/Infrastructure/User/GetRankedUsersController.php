<?php

namespace App\Infrastructure\User;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\User\Ranking\GetRankedUsersService;
use App\Infrastructure\Shared\ValidateRequestQueryDataService;

class GetRankedUsersController
{
    public const TOP_RANKING = 'top';
    public const AT_RANKING = 'at';
    private const REQUIRED_QUERY_FIELDS = ['type'];
    private const RANKING_TYPES = [
        self::AT_RANKING,
        self::TOP_RANKING
    ];

    private GetRankedUsersService $getRankedUsersService;
    private ValidateRequestQueryDataService $validateRequestQueryDataService;

    public function __construct(
        GetRankedUsersService $getRankedUsersService,
        ValidateRequestQueryDataService $validateRequestQueryDataService
    ) {
        $this->getRankedUsersService = $getRankedUsersService;
        $this->validateRequestQueryDataService = $validateRequestQueryDataService;
    }

    /**
     * Method that gets ranked users
     *
     * @return JsonResponse
     */
    public function getRankedUsers(Request $request): JsonResponse
    {
        $response = [];
        $responseMessage = 'No users found';
        $statusCode = 404;

        try {
            $requestQueryData = $request->query->all();
            $this->validateRequestQueryDataService->validate($requestQueryData, self::REQUIRED_QUERY_FIELDS);
            $rankingData = $this->extractRankingTypeData($requestQueryData['type']);
            $rankingType = $rankingData['type'];
            $firstNumber = $rankingData['first_number'];
            $secondNumber = $rankingData['second_number'];

            if (!$this->isValidRankingType($rankingType)) {
                throw new InvalidArgumentException('Invalid type value: ' . $rankingType);
            }

            if (!isset($firstNumber) || $firstNumber < 0) {
                throw new InvalidArgumentException(
                    'For ranking type ' . $rankingType . ' is needed a valid number: topN'
                );
            }

            if ($rankingType === self::AT_RANKING && (!isset($secondNumber) || $secondNumber < 0)) {
                throw new InvalidArgumentException(
                    'For ranking type ' . $rankingType . ' is needed two valid numbers: atN/M'
                );
            }

            $response = $this->getRankedUsersService->execute($rankingType, $firstNumber, $secondNumber);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        if (!empty($response)) {
            $responseMessage = 'Users found successfully';
            $statusCode = 200;
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

    private function extractRankingTypeData(string $type): array
    {
        $result = [];

        if (preg_match('/(.*?)(\d+)\/?(\d+)?/is', $type, $mathces)) {
            $result['type'] = \strtolower($mathces[1]);
            $result['first_number'] = empty($mathces[2]) ? null : \intval($mathces[2]);
            $result['second_number'] = empty($mathces[3]) ? null : \intval($mathces[3]);
        } else {
            throw new InvalidArgumentException('Invalid type value.');
        }

        return $result;
    }

    private function isValidRankingType(string $rankingType): bool
    {
        return \in_array($rankingType, self::RANKING_TYPES);
    }
}
