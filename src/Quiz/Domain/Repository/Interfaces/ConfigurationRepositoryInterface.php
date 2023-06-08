<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Configuration as Entity;
use Throwable;
/**
 * ConfigurationRepositoryInterface
 *
 * @package App\Quiz
 */
interface ConfigurationRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
