<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Question as Entity;
use Throwable;
/**
 * QuestionRepositoryInterface
 *
 * @package App\Quiz
 */
interface QuestionRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
