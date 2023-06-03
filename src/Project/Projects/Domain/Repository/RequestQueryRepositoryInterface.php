<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Repository;

use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;

/**
 * @method findAllByCriteria(Criteria $criteria): RequestListProjection[]
 */
interface RequestQueryRepositoryInterface extends PageableRepositoryInterface
{
}
