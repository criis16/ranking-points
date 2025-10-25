<?php

namespace App\Infrastructure\User\Repositories;

use InvalidArgumentException;

class AddUserRequest
{
    private ?string $id;
    private ?int $totalScore;
    private ?string $operation;
    private ?int $relativeScore;

    public function __construct(
        ?string $id = null,
        ?int $totalScore = null,
        ?string $operation = null,
        ?int $relativeScore = null
    ) {
        $this->id = $id;
        $this->totalScore = $totalScore;
        $this->operation = $operation;
        $this->relativeScore = $relativeScore;
    }

    /**
     * Get the value of id
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the value of totalScore
     *
     * @return integer|null
     */
    public function getTotalScore(): ?int
    {
        return $this->totalScore;
    }

    /**
     * Set the value of totalScore
     *
     * @param integer $totalScore
     * @return void
     */
    public function setTotalScore(int $totalScore): void
    {
        $this->totalScore = $totalScore;
    }

    /**
     * Get the value of operation
     *
     * @return string|null
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * Set the value of operation
     *
     * @param string $operation
     * @return void
     */
    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * Get the value of relativeScore
     *
     * @return integer|null
     */
    public function getRelativeScore(): ?int
    {
        return $this->relativeScore;
    }

    /**
     * Set the value of relativeScore
     *
     * @param integer $relativeScore
     * @return void
     */
    public function setRelativeScore(int $relativeScore): void
    {
        $this->relativeScore = $relativeScore;
    }

    /**
     * Set score data
     *
     * @param array $data
     * @return void
     * @throws InvalidArgumentException
     */
    public function setScore(array $data): void
    {
        if (\array_key_exists('total', $data)) {
            $this->setTotalScore($data['total']);
        }

        if (\array_key_exists('score', $data)) {
            $relativeScoreData = $this->extractRelativeScore($data['score']);

            if (empty($relativeScoreData)) {
                throw new InvalidArgumentException('Invalid score.');
            }

            $this->setOperation($relativeScoreData['operation']);
            $this->setRelativeScore($relativeScoreData['value']);
        }
    }

    /**
     * Extract relative score data
     *
     * @param string $relativeScore
     * @return array
     */
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
