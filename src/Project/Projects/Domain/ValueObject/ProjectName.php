<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\ValueObject;

use App\Project\Shared\Domain\ValueObject\StringValueObject;

final class ProjectName extends StringValueObject
{
    private const MAX_LENGTH = 255;

    protected function ensureIsValid(): void
    {
        $attributeName = 'Project name';
        $this->ensureNotEmpty($attributeName);
        $this->ensureValidMaxLength($attributeName, self::MAX_LENGTH);
    }
}
