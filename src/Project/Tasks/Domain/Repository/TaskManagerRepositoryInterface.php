<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Repository;

use App\Project\Tasks\Domain\Entity\TaskManager;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;

interface TaskManagerRepositoryInterface
{
    public function findByProjectId(ProjectId $id): ?TaskManager;

    public function findByTaskId(TaskId $id): ?TaskManager;

    public function save(TaskManager $manager): void;
}
