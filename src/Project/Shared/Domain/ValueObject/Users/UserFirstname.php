<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\ValueObject\Users;

use App\Project\Shared\Domain\ValueObject\StringValueObject;

final class UserFirstname extends StringValueObject
{
    private const MAX_LENGTH = 255;

    protected function ensureIsValid(): void
    {
        $attributeName = 'User firstname';
        $this->ensureNotEmpty($attributeName);
        $this->ensureValidMaxLength($attributeName, self::MAX_LENGTH);
    }
}
