<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Repository;

use App\Project\Tasks\Domain\Entity\TaskLinkListProjection;
use App\Project\Tasks\Domain\Repository\TaskLinkQueryRepositoryInterface;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Infrastructure\Repository\DoctrineCriteriaRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineTaskLinkQueryRepository implements TaskLinkQueryRepositoryInterface
{
    use DoctrineCriteriaRepositoryTrait;

    private const MANAGER = 'read';

    /**
     * @throws QueryException
     */
    public function findAllByCriteria(Criteria $criteria): array
    {
        return $this->findAllByCriteriaInternal($this->getRepository(), $criteria);
    }

    /**
     * @throws QueryException
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
            TaskLinkListProjection::class,
            self::MANAGER
        );
    }
}
