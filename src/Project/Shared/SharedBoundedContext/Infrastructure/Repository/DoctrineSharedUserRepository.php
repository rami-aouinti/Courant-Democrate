<?php

declare(strict_types=1);

namespace App\Project\Shared\SharedBoundedContext\Infrastructure\Repository;

use App\Project\Shared\SharedBoundedContext\Domain\Entity\SharedUser;
use App\Project\Shared\SharedBoundedContext\Domain\Repository\SharedUserRepositoryInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineSharedUserRepository implements SharedUserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param string $id
     * @return SharedUser|null
     */
    public function findById(string $id): ?SharedUser
    {
        return $this->getRepository()->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @param SharedUser $user
     */
    public function save(SharedUser $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(SharedUser::class);
    }
}
