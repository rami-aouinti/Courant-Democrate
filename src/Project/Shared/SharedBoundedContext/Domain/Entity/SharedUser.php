<?php

declare(strict_types=1);

namespace App\Project\Shared\SharedBoundedContext\Domain\Entity;

use App\Project\Shared\Domain\Aggregate\AggregateRoot;

final class SharedUser extends AggregateRoot
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
