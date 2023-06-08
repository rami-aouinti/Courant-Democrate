<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Workout as Entity;
use Throwable;
/**
 * WorkoutRepositoryInterface
 *
 * @package App\Quiz
 */
interface WorkoutRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
