<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Repository;

use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;
use App\Project\Projects\Domain\Entity\ProjectProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): ProjectListProjection[]
 */
interface ProjectQueryRepositoryInterface extends PageableRepositoryInterface
{
    public function findByCriteria(Criteria $criteria): ?ProjectProjection;
}
