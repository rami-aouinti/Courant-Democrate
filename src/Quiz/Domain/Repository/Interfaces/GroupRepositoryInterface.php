<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Group as Entity;
use Throwable;
/**
 * GroupRepositoryInterface
 *
 * @package App\Quiz
 */
interface GroupRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
