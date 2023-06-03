<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Service;

use App\Project\Shared\Domain\Criteria\Criteria;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;

interface CriteriaToDoctrineCriteriaConverterInterface
{
    public function convert(Criteria $criteria): DoctrineCriteria;
}
