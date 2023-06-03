<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\ValueObject;

use App\Project\Shared\Domain\ValueObject\StringValueObject;

final class TaskName extends StringValueObject
{
    private const MAX_LENGTH = 255;

    protected function ensureIsValid(): void
    {
        $attributeName = 'Task name';
        $this->ensureNotEmpty($attributeName);
        $this->ensureValidMaxLength($attributeName, self::MAX_LENGTH);
    }
}
