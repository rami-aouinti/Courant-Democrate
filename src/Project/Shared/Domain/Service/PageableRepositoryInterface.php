<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\Service;

use App\Project\Shared\Domain\Criteria\Criteria;

interface PageableRepositoryInterface
{
    public function findAllByCriteria(Criteria $criteria): array;

    public function findCountByCriteria(Criteria $criteria): int;
}
