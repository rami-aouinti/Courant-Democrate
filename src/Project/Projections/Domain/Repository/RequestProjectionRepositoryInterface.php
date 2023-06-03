<?php

declare(strict_types=1);

namespace App\Project\Projections\Domain\Repository;

use App\Project\Projections\Domain\Entity\RequestProjection;

interface RequestProjectionRepositoryInterface
{
    /**
     * @return RequestProjection[]
     */
    public function findByUserId(string $id): array;

    public function findById(string $id): ?RequestProjection;

    public function save(RequestProjection $projection): void;
}
