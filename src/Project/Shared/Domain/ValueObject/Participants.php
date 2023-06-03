<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\ValueObject;

use App\Project\Shared\Domain\Collection\UserIdCollection;
use App\Project\Shared\Domain\Exception\ProjectParticipantNotExistException;
use App\Project\Shared\Domain\Exception\UserIsAlreadyParticipantException;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class Participants
{
    private UserIdCollection $participants;

    public function __construct(?UserIdCollection $items = null)
    {
        if (null === $items) {
            $this->participants = new UserIdCollection();
        } else {
            $this->participants = $items;
        }
    }

    public function ensureIsParticipant(UserId $userId): void
    {
        if (!$this->isParticipant($userId)) {
            throw new ProjectParticipantNotExistException($userId->value);
        }
    }

    public function ensureIsNotParticipant(UserId $userId): void
    {
        if ($this->isParticipant($userId)) {
            throw new UserIsAlreadyParticipantException($userId->value);
        }
    }

    public function isParticipant(UserId $userId): bool
    {
        return $this->participants->exists($userId);
    }

    public function add(UserId $userId): self
    {
        $result = new self();
        $result->participants = $this->participants->add($userId);

        return $result;
    }

    public function remove(UserId $userId): self
    {
        $result = new self();
        $result->participants = $this->participants->remove($userId);

        return $result;
    }

    public function getCollection(): UserIdCollection
    {
        return $this->participants;
    }
}
