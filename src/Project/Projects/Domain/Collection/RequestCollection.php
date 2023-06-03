<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Collection;

use App\Project\Shared\Domain\Collection\Collection;
use App\Project\Projects\Domain\Entity\Request;

final class RequestCollection extends Collection
{
    protected function getType(): string
    {
        return Request::class;
    }
}
