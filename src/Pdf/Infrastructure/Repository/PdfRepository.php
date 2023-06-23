<?php

declare(strict_types=1);

namespace App\Pdf\Infrastructure\Repository;

use App\General\Domain\Rest\UuidHelper;
use App\General\Infrastructure\Repository\BaseRepository;
use App\Pdf\Domain\Repository\Interfaces\PdfRepositoryInterface;
use App\Pdf\Domain\Entity\Document as Entity;
use App\User\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;

use function array_key_exists;

/**
 * Class OfficeRepository
 *
 * @package App\Document
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
class PdfRepository extends BaseRepository implements PdfRepositoryInterface
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
