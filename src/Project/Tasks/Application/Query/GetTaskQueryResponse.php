<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Query;

use App\Project\Tasks\Domain\Entity\TaskProjection;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;

final class GetTaskQueryResponse implements QueryResponseInterface
{
    private readonly TaskProjection $task;

    public function __construct(TaskProjection $task)
    {
        $this->task = $task;
    }

    public function getTask(): TaskProjection
    {
        return $this->task;
    }
}
