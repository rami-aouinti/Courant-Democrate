<?php

declare(strict_types=1);

namespace App\Article\Domain\Repository\Interfaces;


use App\Article\Domain\Entity\Tag as Entity;
use Throwable;
/**
 * SettingRepositoryInterface
 *
 * @package App\Setting
 */
interface TagRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
