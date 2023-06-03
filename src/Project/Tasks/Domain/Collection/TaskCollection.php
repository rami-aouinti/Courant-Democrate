<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Collection;

use App\Project\Tasks\Domain\Entity\Task;
use App\Project\Shared\Domain\Collection\Collection;

final class TaskCollection extends Collection
{
    protected function getType(): string
    {
        return Task::class;
    }
}
