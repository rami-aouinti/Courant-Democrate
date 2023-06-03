<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\ValueObject;

use App\Project\Shared\Domain\ValueObject\DateTime;

final class TaskInformation
{
    public function __construct(
        public readonly TaskName $name,
        public readonly TaskBrief $brief,
        public readonly TaskDescription $description,
        public readonly DateTime $startDate,
        public readonly DateTime $finishDate,
    ) {
    }

    public function isEqual(object $other): bool
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        return $this->name->value === $other->name->value &&
            $this->brief->value === $other->brief->value &&
            $this->description->value === $other->description->value &&
            $this->startDate->isEqual($other->startDate) &&
            $this->finishDate->isEqual($other->finishDate);
    }
}
