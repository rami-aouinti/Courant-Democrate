<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Entity;

final class ProjectListProjection
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $name,
        public readonly string $finishDate,
        public readonly string $ownerId,
        public readonly string $ownerFirstname,
        public readonly string $ownerLastname,
        public readonly string $ownerEmail,
        public readonly int $status,
        public readonly int $tasksCount,
        public readonly int $participantsCount,
        public readonly int $pendingRequestsCount
    ) {
    }
}
