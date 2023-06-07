<?php

declare(strict_types=1);

namespace App\Event\Domain\Repository\Interfaces;

use App\Event\Domain\Entity\Event as Entity;
use Throwable;

/**
 * EventRepositoryInterface
 *
 * @package App\Event
 */
interface EventRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
