<?php

declare(strict_types=1);

namespace App\Setting\Domain\Repository\Interfaces;


use App\Setting\Domain\Entity\Menu as Entity;
use Throwable;
/**
 * MenuRepositoryInterface
 *
 * @package App\Setting
 */
interface MenuRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
