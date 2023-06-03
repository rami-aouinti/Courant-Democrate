<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryInterface;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;

final class GetTaskLinksQuery implements QueryInterface
{
    public function __construct(
        public readonly string $id,
        public readonly RequestCriteriaDTO $criteria
    ) {
    }
}
