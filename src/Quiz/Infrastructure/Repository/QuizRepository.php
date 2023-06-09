<?php

namespace App\Quiz\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Entity\Quiz as Entity;
use App\Quiz\Domain\Repository\Interfaces\QuizRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @method Quiz|null find(string $id, ?int $lockMode = null, ?int $lockVersion = null, ?string $entityManagerName = null)
 * @method Entity|null findAdvanced(string $id, string | int | null $hydrationMode = null, string|null $entityManagerName = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?string $entityManagerName = null)
 * @method Entity[] findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?string $entityManagerName = null)
 * @method Entity[] findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null, ?string $entityManagerName = null)
 * @method Entity[] findAll(?string $entityManagerName = null)
 */
class QuizRepository extends BaseRepository implements QuizRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Quiz::class;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        protected ManagerRegistry $managerRegistry
    ) {
    }


    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity
    {
        // Create new entity
        $entity = new Entity();
        // Store entity to database
        $this->save($entity);

        return $entity;
    }

}
