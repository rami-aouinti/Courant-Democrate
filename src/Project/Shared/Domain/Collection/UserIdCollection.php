<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\Collection;

use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class UserIdCollection extends Collection
{
    protected function getType(): string
    {
        return UserId::class;
    }
}
