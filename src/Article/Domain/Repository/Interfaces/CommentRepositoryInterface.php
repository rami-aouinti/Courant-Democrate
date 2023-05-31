<?php

declare(strict_types=1);

namespace App\Article\Domain\Repository\Interfaces;


use App\Article\Domain\Entity\Comment as Entity;
use Throwable;
/**
 * CommentRepositoryInterface
 *
 * @package App\Article
 */
interface CommentRepositoryInterface
{

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity;
}
