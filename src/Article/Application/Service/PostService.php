<?php

declare(strict_types=1);

namespace App\Article\Application\Service;

use App\Article\Domain\Entity\Post;
use App\Article\Infrastructure\Repository\PostRepository;
use Throwable;

/**
 * Class HealthService
 *
 * @package App\Tool
 */
class PostService
{
    /**
     * @param PostRepository $repository
     */
    public function __construct(
        private readonly PostRepository $repository,
    ) {
    }

    /**
     * Method to check that "all" is ok within our application. This will try to do following:
     *  1) Remove data from database
     *  2) Create data to database
     *  3) Read data from database
     *
     * These steps should make sure that at least application database is working as expected.
     *
     * @throws Throwable
     */
    public function getPosts(): ?array
    {
        return $this->repository->read();
    }

    /**
     * Method to check that "all" is ok within our application. This will try to do following:
     *  1) Remove data from database
     *  2) Create data to database
     *  3) Read data from database
     *
     * These steps should make sure that at least application database is working as expected.
     *
     * @throws Throwable
     */
    public function getPost($slug): ?Post
    {
        return $this->repository->find($slug);
    }
}
