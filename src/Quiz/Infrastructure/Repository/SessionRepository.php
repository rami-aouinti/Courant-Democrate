<?php

namespace App\Quiz\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Quiz\Domain\Entity\Session as Entity;
use App\Quiz\Domain\Entity\Session;
use App\Quiz\Domain\Repository\Interfaces\SessionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends BaseRepository implements SessionRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Session::class;
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

    public function removeDuplicateWorkouts(int $session_id, int $quiz_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "DELETE FROM tbl_workout WHERE session_id = :session_id AND quiz_id = :quiz_id AND number_of_questions < 1
                    AND student_id IN (SELECT student_id AS id FROM tbl_workout WHERE session_id = :session_id AND quiz_id = :quiz_id AND number_of_questions > 0);";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('session_id', $session_id);
        $stmt->bindParam('quiz_id', $quiz_id);
        $stmt->executeStatement();
    }

    public function cleanByQuizId(int $quiz_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "delete from tbl_session where quiz_id = :quiz_id
                and :quiz_id not in (select id as quiz_id from tbl_quiz where active > 0)
                and id not in (select session_id as id from tbl_workout where session_id is not null and quiz_id = :quiz_id);";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('quiz_id', $quiz_id);
        $stmt->executeStatement();
    }

    public function findByQuizId($quiz_id)
    {
        $builder = $this->createQueryBuilder('s');
        $builder->andWhere('s.quiz = :val')->setParameter('val', $quiz_id);
        $builder->orderBy('s.id', 'DESC');
        return $builder->getQuery()->getResult();
    }

    // /**
    //  * @return Session[] Returns an array of Session objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
