<?php

declare(strict_types=1);

namespace App\Setting\Domain\Repository\Interfaces;


use App\Setting\Domain\Entity\Setting as Entity;
use Throwable;
/**
 * SettingRepositoryInterface
 *
 * @package App\Setting
 */
interface SettingRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
