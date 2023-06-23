<?php

declare(strict_types=1);

namespace App\Politic\Infrastructure\Repository;

use App\General\Domain\Rest\UuidHelper;
use App\General\Infrastructure\Repository\BaseRepository;
use App\Politic\Domain\Repository\Interfaces\OfficeRepositoryInterface;
use App\Politic\Domain\Entity\Office as Entity;
use App\User\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;

use function array_key_exists;

/**
 * Class OfficeRepository
 *
 * @package App\Office
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 * @codingStandardsIgnoreStart
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
class OfficeRepository extends BaseRepository implements OfficeRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        protected ManagerRegistry $managerRegistry,
    ) {
    }

}
