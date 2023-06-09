<?php

namespace App\Quiz\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Quiz\Domain\Entity\Question as Entity;
use App\Quiz\Domain\Entity\Question;
use App\Quiz\Domain\Repository\Interfaces\QuestionRepositoryInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * @method Question|null find(string $id, ?int $lockMode = null, ?int $lockVersion = null, ?string $entityManagerName = null)
 * @method Entity|null findAdvanced(string $id, string | int | null $hydrationMode = null, string|null $entityManagerName = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?string $entityManagerName = null)
 * @method Entity[] findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?string $entityManagerName = null)
 * @method Entity[] findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null, ?string $entityManagerName = null)
 * @method Entity[] findAll(?string $entityManagerName = null)
 */
class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Question::class;
    public function __construct(
        protected ManagerRegistry $managerRegistry,
    ) {
    }

    /**
     * Method to write new value to database.
     *
     * @return Question
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(): Question
    {
        // Create new entity
        $entity = new Entity();
        // Store entity to database
        $this->save($entity);

        return $entity;
    }
}
