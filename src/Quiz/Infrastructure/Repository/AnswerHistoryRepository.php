<?php

namespace App\Quiz\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Quiz\Domain\Entity\AnswerHistory;
use App\Quiz\Domain\Repository\Interfaces\AnswerHistoryRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Quiz\Domain\Entity\AnswerHistory as Entity;
use Throwable;

/**
 * @method AnswerHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerHistory[]    findAll()
 * @method AnswerHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerHistoryRepository extends BaseRepository implements AnswerHistoryRepositoryInterface
{

    /**
     * @psalm-var class-string
     */
    protected static string $entityName = AnswerHistory::class;

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

//    /**
//     * @return AnswerHistory[] Returns an array of AnswerHistory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnswerHistory
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
