<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\ValueObject;

use App\Project\Shared\Domain\ValueObject\StringValueObject;

final class ProjectDescription extends StringValueObject
{
    private const MAX_LENGTH = 4000;

    protected function ensureIsValid(): void
    {
        $this->ensureValidMaxLength('Project description', self::MAX_LENGTH);
    }
}
