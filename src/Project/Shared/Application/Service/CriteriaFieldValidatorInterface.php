<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\Project\Shared\Domain\Criteria\Criteria;

interface CriteriaFieldValidatorInterface
{
    public function validate(Criteria $criteria, string $class): void;
}
