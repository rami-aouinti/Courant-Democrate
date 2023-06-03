<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Repository;

use App\Project\Tasks\Domain\Entity\TaskProjection;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;

/**
 * @method findAllByCriteria(Criteria $criteria): TaskListProjection[]
 */
interface TaskQueryRepositoryInterface extends PageableRepositoryInterface
{
    public function findByCriteria(Criteria $criteria): ?TaskProjection;
}
