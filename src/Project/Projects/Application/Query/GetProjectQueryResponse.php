<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Projects\Domain\Entity\ProjectProjection;

final class GetProjectQueryResponse implements QueryResponseInterface
{
    private readonly ProjectProjection $project;

    public function __construct(ProjectProjection $project)
    {
        $this->project = $project;
    }

    public function getProject(): ProjectProjection
    {
        return $this->project;
    }
}
