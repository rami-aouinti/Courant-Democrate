<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\ValueObject;

final class ClosedTaskStatus extends TaskStatus
{
    public function allowsModification(): bool
    {
        return false;
    }

    protected function getNextStatuses(): array
    {
        return [ActiveTaskStatus::class];
    }
}
