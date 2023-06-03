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
        public readonly UserId $userId
    ) {
    }

    public function ensureIsOwner(UserId $userId): void
    {
        if (!$this->isOwner($userId)) {
            throw new UserIsNotOwnerException($userId->value);
        }
    }

    public function ensureIsNotOwner(UserId $userId): void
    {
        if ($this->isOwner($userId)) {
            throw new UserIsAlreadyOwnerException($userId->value);
        }
    }

    public function isOwner(UserId $userId): bool
    {
        return $this->userId->isEqual($userId);
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

        return $this->userId->isEqual($other->userId);
    }
}
