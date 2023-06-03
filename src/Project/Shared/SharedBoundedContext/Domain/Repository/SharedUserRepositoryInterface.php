<?php

declare(strict_types=1);

namespace App\Project\Shared\SharedBoundedContext\Domain\Repository;

use App\Project\Shared\SharedBoundedContext\Domain\Entity\SharedUser;

interface SharedUserRepositoryInterface
{
    public function findById(string $id): ?SharedUser;

    public function save(SharedUser $user): void;
}
