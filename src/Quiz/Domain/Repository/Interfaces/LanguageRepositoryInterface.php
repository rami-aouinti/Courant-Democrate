<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository\Interfaces;


use App\Quiz\Domain\Entity\Language as Entity;
use Throwable;
/**
 * LanguageRepositoryInterface
 *
 * @package App\Quiz
 */
interface LanguageRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
