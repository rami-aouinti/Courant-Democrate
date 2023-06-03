<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Collection;

use App\Project\Tasks\Domain\ValueObject\TaskLink;
use App\Project\Shared\Domain\Collection\Collection;

final class TaskLinkCollection extends Collection
{
    protected function getType(): string
    {
        return TaskLink::class;
    }
}
