<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\ValueObject\Requests;

final class PendingRequestStatus extends RequestStatus
{
    protected function getNextStatuses(): array
    {
        return [
            RejectedRequestStatus::class,
            ConfirmedRequestStatus::class,
        ];
    }
}
