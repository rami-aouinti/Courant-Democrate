<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryInterface;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;

final readonly class GetAllOwnProjectsQuery implements QueryInterface
{
    public function __construct(
        public RequestCriteriaDTO $criteria
    ) {
    }
}
