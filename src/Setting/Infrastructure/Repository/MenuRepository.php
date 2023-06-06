<?php

namespace App\Setting\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Setting\Domain\Entity\Menu as Entity;
use App\Setting\Domain\Repository\Interfaces\MenuRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 *
 * @package App\Setting
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 * @codingStandardsIgnoreStart
 *
 *
 * @method Entity|null find(string $id, ?int $lockMode = null, ?int $lockVersion = null, ?string $entityManagerName = null)
 * @method Entity|null findAdvanced(string $id, string | int | null $hydrationMode = null, string|null $entityManagerName = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?string $entityManagerName = null)
 * @method Entity[] findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?string $entityManagerName = null)
 * @method Entity[] findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null, ?string $entityManagerName = null)
 * @method Entity[] findAll(?string $entityManagerName = null)
 *
 * @codingStandardsIgnoreEnd
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Entity::class;

    public function __construct(
        protected ManagerRegistry $managerRegistry,
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
