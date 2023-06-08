<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\School as Entity;
use Throwable;
/**
 * SchoolRepositoryInterface
 *
 * @package App\Quiz
 */
interface SchoolRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
