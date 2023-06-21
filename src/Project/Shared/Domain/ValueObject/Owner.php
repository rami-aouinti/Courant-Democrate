<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\ValueObject;

use App\Project\Shared\Domain\Collection\Hashable;
use App\Project\Shared\Domain\Exception\UserIsAlreadyOwnerException;
use App\Project\Shared\Domain\Exception\UserIsNotOwnerException;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class Owner implements Hashable
{
    public function __construct(
        public readonly string $userId
    ) {
    }

    public function ensureIsOwner(string $userId): void
    {
        if (!$this->isOwner($userId)) {
            throw new UserIsNotOwnerException($userId);
        }
    }

    public function ensureIsNotOwner(string $userId): void
    {
        if ($this->isOwner($userId)) {
            throw new UserIsAlreadyOwnerException($userId);
        }
    }

    public function isOwner(string $userId): bool
    {
        return ($this->userId === $userId);
    }

    public function getHash(): string
    {
        return $this->userId->getHash();
    }

    public function isEqual(object $other): bool
    {
        if (!($other instanceof self)) {
            return false;
        }

        return $this->userId === $other->userId;
    }
}
