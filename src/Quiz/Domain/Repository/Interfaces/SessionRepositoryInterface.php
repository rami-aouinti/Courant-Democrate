<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Session as Entity;
use Throwable;
/**
 * SessionRepositoryInterface
 *
 * @package App\Quiz
 */
interface SessionRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
