<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Repository;

use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;

/**
 * @method findAllByCriteria(Criteria $criteria): TaskLinkListProjection[]
 */
interface TaskLinkQueryRepositoryInterface extends PageableRepositoryInterface
{
}
