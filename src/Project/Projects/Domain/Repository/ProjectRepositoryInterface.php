<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Repository;

use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\ValueObject\RequestId;

interface ProjectRepositoryInterface
{
    public function findById(ProjectId $id): ?Project;

    public function findByRequestId(RequestId $id): ?Project;

    public function save(Project $project): void;
}
