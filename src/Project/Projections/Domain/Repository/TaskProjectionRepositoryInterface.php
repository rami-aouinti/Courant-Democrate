<?php

declare(strict_types=1);

namespace App\Project\Projections\Domain\Repository;

use App\Project\Projections\Domain\Entity\TaskProjection;

interface TaskProjectionRepositoryInterface
{
    /**
     * @return TaskProjection[]
     */
    public function findAllByOwnerId(string $id): array;

    public function findById(string $id): ?TaskProjection;

    public function save(TaskProjection $projection): void;
}
