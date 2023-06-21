<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Entity;

final readonly class ProjectProjection
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $name,
        public string $description,
        public string $finishDate,
        public string $ownerId,
        public string $ownerFirstname,
        public string $ownerLastname,
        public string $ownerEmail,
        public int    $status
    ) {
    }
}
