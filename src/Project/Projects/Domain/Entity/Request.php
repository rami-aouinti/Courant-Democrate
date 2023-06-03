<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Entity;

use App\Project\Shared\Domain\Collection\Hashable;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Requests\PendingRequestStatus;
use App\Project\Shared\Domain\ValueObject\Requests\RequestStatus;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\ValueObject\RequestId;

final class Request implements Hashable
{
    public function __construct(
        private RequestId $id,
        private UserId $userId,
        private RequestStatus $status,
        private DateTime $changeDate
    ) {
    }

    public static function create(RequestId $id, UserId $userId): self
    {
        $status = new PendingRequestStatus();
        $changeDate = new DateTime();

        return new Request($id, $userId, $status, $changeDate);
    }

    public function changeStatus(RequestStatus $status): void
    {
        $this->status->ensureCanBeChangedTo($status);
        $this->status = $status;
        $this->changeDate = new DateTime();
    }

    public function getId(): RequestId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getStatus(): RequestStatus
    {
        return $this->status;
    }

    public function getChangeDate(): DateTime
    {
        return $this->changeDate;
    }

    public function getHash(): string
    {
        return $this->getId()->getHash();
    }

    public function isEqual(object $other): bool
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        return $this->id->isEqual($other->id) &&
            $this->userId->isEqual($other->userId) &&
            $this->status->isEqual($other->status) &&
            $this->changeDate->isEqual($other->changeDate);
    }

    public function isPending(): bool
    {
        return $this->status instanceof PendingRequestStatus;
    }
}
