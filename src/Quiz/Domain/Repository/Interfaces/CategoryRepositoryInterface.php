<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Category as Entity;
use Throwable;
/**
 * CategoryRepositoryInterface
 *
 * @package App\Quiz
 */
interface CategoryRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}