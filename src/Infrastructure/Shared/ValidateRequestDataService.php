<?php

namespace App\Infrastructure\Shared;

use App\Infrastructure\Shared\Exceptions\RequestException;

class ValidateRequestDataService
{
    /**
     * Validates the request input data
     *
     * @param array $request
     * @param array $requiredFields
     * @throws RequestException
     * @return void
     */
    public function validate(
        array $request,
        array $requiredFields
    ): void {
        if (empty($request)) {
            throw new RequestException(['empty_body' => 'There is not request data.']);
        }

        $message = [];

        foreach ($request as $requestKey => $requestValue) {
            if (!\in_array($requestKey, $requiredFields) || empty($requestKey)) {
                $message[$requestKey] = \ucfirst($requestKey) . ' is a required field';
            }
        }

        if (!empty($message)) {
            throw new RequestException($message);
        }
    }
}
