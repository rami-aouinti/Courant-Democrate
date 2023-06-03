<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Repository;

use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Infrastructure\Repository\DoctrineCriteriaRepositoryTrait;
use App\Project\Projects\Domain\Entity\ProjectListProjection;
use App\Project\Projects\Domain\Entity\ProjectProjection;
use App\Project\Projects\Domain\Repository\ProjectQueryRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineProjectQueryRepository implements ProjectQueryRepositoryInterface
{
    use DoctrineCriteriaRepositoryTrait;

    private const MANAGER = 'read';

    /**
     * @return ProjectListProjection[]
     *
     * @throws QueryException
     */
    public function findAllByCriteria(Criteria $criteria): array
    {
        return $this->findAllByCriteriaInternal($this->getListRepository(), $criteria);
    }

    /**
     * @throws QueryException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findCountByCriteria(Criteria $criteria): int
    {
        return $this->findCountByCriteriaInternal($this->getListRepository(), $criteria);
    }

    /**
     * @throws NonUniqueResultException
     * @throws QueryException
     */
    public function findByCriteria(Criteria $criteria): ?ProjectProjection
    {
        return $this->findByCriteriaInternal($this->getRepository(), $criteria);
    }

    private function getListRepository(): ObjectRepository|EntityRepository
    {
        return $this->managerRegistry->getRepository(
            ProjectListProjection::class,
            self::MANAGER
        );
    }

    private function getRepository(): ObjectRepository|EntityRepository
    {
        return $this->managerRegistry->getRepository(
            ProjectProjection::class,
            self::MANAGER
        );
    }
}
