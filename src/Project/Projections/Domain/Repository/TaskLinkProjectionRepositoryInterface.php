<?php

declare(strict_types=1);

namespace App\Project\Projections\Domain\Repository;

use App\Project\Projections\Domain\Entity\TaskLinkProjection;

interface TaskLinkProjectionRepositoryInterface
{
    public function findById(string $id, string $toId): ?TaskLinkProjection;

    public function save(TaskLinkProjection $projection): void;

    public function delete(TaskLinkProjection $projection): void;
}
