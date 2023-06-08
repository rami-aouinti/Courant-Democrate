<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\QuestionHistory as Entity;
use Throwable;
/**
 * QuestionHistoryRepositoryInterface
 *
 * @package App\Quiz
 */
interface QuestionHistoryRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
