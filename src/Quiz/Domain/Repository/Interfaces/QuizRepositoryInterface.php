<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Quiz as Entity;
use Throwable;
/**
 * QuizRepositoryInterface
 *
 * @package App\Quiz
 */
interface QuizRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
