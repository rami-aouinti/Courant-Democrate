<?php

declare(strict_types=1);

namespace App\Setting\Domain\Repository\Interfaces;


use App\Setting\Domain\Entity\Component as Entity;
use Throwable;
/**
 * ComponentRepositoryInterface
 *
 * @package App\Setting
 */
interface ComponentRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
