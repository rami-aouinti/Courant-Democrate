<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\AnswerHistory as Entity;
use Throwable;
/**
 * AnswerHistoryRepositoryInterface
 *
 * @package App\Quiz
 */
interface AnswerHistoryRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
