<?php

declare(strict_types=1);

namespace App\Project\Projections\Infrastructure\Repository;

use App\Project\Projections\Domain\Entity\ProjectProjection;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineProjectProjectionRepository implements ProjectProjectionRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(ProjectProjection $projection): void
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();
    }

    public function findAllById(string $id): array
    {
        return $this->getRepository()->findBy([
            'id' => $id,
        ]);
    }

    public function findAllByOwnerId(string $id): array
    {
        return $this->getRepository()->findBy([
            'ownerId' => $id,
        ]);
    }

    public function delete(ProjectProjection $projection): void
    {
        $this->entityManager->remove($projection);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(ProjectProjection::class);
    }
}
