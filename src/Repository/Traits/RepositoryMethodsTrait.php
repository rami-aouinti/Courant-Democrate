<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use App\Entity\Interfaces\EntityInterface;
use App\Rest\RepositoryHelper;
use App\Rest\UuidHelper;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\TransactionRequiredException;
use InvalidArgumentException;

use function array_column;

/**
 * Trait RepositoryMethodsTrait
 *
 * @package App\Repository\Traits
 */
trait RepositoryMethodsTrait
{
    /**
     * Wrapper for default Doctrine repository find method.
     *
     * @throws TransactionRequiredException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     */
    public function find(string $id, ?int $lockMode = null, ?int $lockVersion = null): ?EntityInterface
    {
        $output = $this->getEntityManager()->find($this->getEntityName(), $id, $lockMode, $lockVersion);

        return $output instanceof EntityInterface ? $output : null;
    }

    /**
     * {@inheritdoc}
     */
    public function findAdvanced(string $id, string | int | null $hydrationMode = null): null | array | EntityInterface
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder();
        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);
        $queryBuilder
            ->where('entity.id = :id')
            ->setParameter('id', $id, UuidHelper::getType($id));
        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */

        return $queryBuilder->getQuery()->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        $repository = $this->getEntityManager()->getRepository($this->getEntityName());

        return $repository->findOneBy($criteria, $orderBy);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return list<object|EntityInterface>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityName())
            ->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @return array<int, EntityInterface>
     */
    public function findByAdvanced(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null
    ): array {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search, $orderBy, $limit, $offset);
        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);
        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        /** @psalm-suppress InvalidTemplateParam */
        $iterator = (new Paginator($queryBuilder, true))->getIterator();

        return $iterator->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return list<object|EntityInterface>
     */
    public function findAll(): array
    {
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityName())
            ->findAll();
    }

    /**
     * {@inheritdoc}
     *
     * @return array<int, string>
     */
    public function findIds(?array $criteria = null, ?array $search = null): array
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);
        // Build query
        $queryBuilder
            ->select('entity.id')
            ->distinct();
        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);
        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        return array_column($queryBuilder->getQuery()->getArrayResult(), 'id');
    }

    /**
     * Generic count method to determine count of entities for specified criteria and search term(s).
     *
     * @throws InvalidArgumentException|NonUniqueResultException|NoResultException
     */
    public function countAdvanced(?array $criteria = null, ?array $search = null): int
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);
        // Build query
        $queryBuilder->select('COUNT(DISTINCT(entity.id))');
        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);
        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * Helper method to 'reset' repository entity table - in other words delete all records
     */
    public function reset(): int
    {
        // Create query builder
        $queryBuilder = $this->createQueryBuilder();
        // Define delete query
        $queryBuilder->delete();

        // Return deleted row count
        return (int)$queryBuilder->getQuery()->execute();
    }

    /**
     * Helper method to get QueryBuilder for current instance within specified default parameters.
     *
     * @param array<int|string, mixed>|null $criteria
     * @param array<string, string>|null $search
     * @param array<string, string>|null $orderBy
     *
     * @throws InvalidArgumentException
     */
    private function getQueryBuilder(
        ?array $criteria = null,
        ?array $search = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): QueryBuilder {
        // Create new QueryBuilder for this instance
        $queryBuilder = $this->createQueryBuilder();
        // Process normal and search term criteria
        RepositoryHelper::processCriteria($queryBuilder, $criteria);
        RepositoryHelper::processSearchTerms($queryBuilder, $this->getSearchColumns(), $search);
        RepositoryHelper::processOrderBy($queryBuilder, $orderBy);
        // Process limit and offset
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset ?? 0);

        return $queryBuilder;
    }
}
