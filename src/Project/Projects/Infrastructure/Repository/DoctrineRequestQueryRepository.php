<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Repository;

use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Infrastructure\Repository\DoctrineCriteriaRepositoryTrait;
use App\Project\Projects\Domain\Entity\RequestListProjection;
use App\Project\Projects\Domain\Repository\RequestQueryRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;
use ReflectionException;

final class DoctrineRequestQueryRepository implements RequestQueryRepositoryInterface
{
    use DoctrineCriteriaRepositoryTrait;

    private const MANAGER = 'read';

    /**
     * @throws QueryException
     * @throws ReflectionException
     */
    public function findAllByCriteria(Criteria $criteria): array
    {
        return $this->findAllByCriteriaInternal($this->getRepository(), $criteria);
    }

    /**
     * @throws QueryException
     * @throws ReflectionException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findCountByCriteria(Criteria $criteria): int
    {
        return $this->findCountByCriteriaInternal($this->getRepository(), $criteria);
    }

    private function getRepository(): ObjectRepository|EntityRepository
    {
        return $this->managerRegistry->getRepository(
            RequestListProjection::class,
            self::MANAGER
        );
    }
}
