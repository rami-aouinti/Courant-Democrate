<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Repository;

use App\Project\Tasks\Domain\Entity\TaskListProjection;
use App\Project\Tasks\Domain\Entity\TaskProjection;
use App\Project\Tasks\Domain\Repository\TaskQueryRepositoryInterface;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Infrastructure\Repository\DoctrineCriteriaRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineTaskQueryRepository implements TaskQueryRepositoryInterface
{
    use DoctrineCriteriaRepositoryTrait;

    private const MANAGER = 'read';

    /**
     * @return TaskListProjection[]
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
    public function findByCriteria(Criteria $criteria): ?TaskProjection
    {
        return $this->findByCriteriaInternal($this->getRepository(), $criteria);
    }

    private function getListRepository(): ObjectRepository|EntityRepository
    {
        return $this->managerRegistry->getRepository(
            TaskListProjection::class,
            self::MANAGER
        );
    }

    private function getRepository(): ObjectRepository|EntityRepository
    {
        return $this->managerRegistry->getRepository(
            TaskProjection::class,
            self::MANAGER
        );
    }
}
