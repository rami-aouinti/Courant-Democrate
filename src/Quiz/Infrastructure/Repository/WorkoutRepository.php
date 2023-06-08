<?php

namespace App\Quiz\Infrastructure\Repository;

use App\General\Infrastructure\Repository\BaseRepository;
use App\Quiz\Domain\Entity\Workout as Entity;
use App\Quiz\Domain\Entity\Workout;
use App\Quiz\Domain\Repository\Interfaces\WorkoutRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Workout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workout[]    findAll()
 * @method Workout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkoutRepository extends BaseRepository implements WorkoutRepositoryInterface
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Workout::class;
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


    public function findLastNotCompletedByStudent($user): ?Workout
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.completed = :completed')
            ->andWhere('w.student = :student')
            ->setParameter('completed', false)
            ->setParameter('student', $user)
            ->orderBy('w.ended_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByQuizAndDate($quiz, $date, string $orderBy = 'number_of_questions'): ?array
    {
        $builder = $this->createQueryBuilder('w')
            ->andWhere('w.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->andWhere('w.started_at >= :started_at')
            ->setParameter('started_at', $date)
            ->groupBy('w.student');
        switch ($orderBy) {
            case 'number_of_questions':
                $builder->orderBy('w.number_of_questions', 'ASC');
                break;
            case 'started_at':
                $builder->orderBy('w.started_at', 'ASC');
                break;
            default:
                $builder->orderBy('w.started_at', 'ASC');
                break;
        }
        // $builder->addOrderBy('w.score', 'DESC')
        // $builder->addOrderBy('w.ended_at', 'DESC')
        return $builder->getQuery()->getResult();
    }

    public function findFirstThreeByQuizAndDate($quiz, $date): ?array
    {
        $builder = $this->createQueryBuilder('w');
        $builder->andWhere('w.quiz = :quiz')->setParameter('quiz', $quiz);
        $builder->andWhere('w.started_at >= :started_at')->setParameter('started_at', $date);
        $builder->groupBy('w.student')
            ->orderBy('w.score', 'DESC')
            ->addOrderBy('TIME_DIFF(w.ended_at, w.started_at, \'second\')', 'ASC');
        return $builder->getQuery()->setMaxResults(3)->getResult();
    }

    public function findByQuizAndSession($quiz, $session, string $orderBy = 'number_of_questions'): ?array
    {
        $builder = $this->createQueryBuilder('w');
        $builder->andWhere('w.quiz = :quiz')->setParameter('quiz', $quiz);
        $builder->andWhere('w.session >= :session')->setParameter('session', $session);
        $builder->groupBy('w.student');
        switch ($orderBy) {
            case 'number_of_questions':
                $builder->orderBy('w.number_of_questions', 'ASC');
                $builder->addOrderBy('w.started_at', 'ASC');
                break;
            case 'started_at':
                $builder->orderBy('w.started_at', 'ASC');
                break;
            case 'score':
                $builder->orderBy('w.score', 'DESC');
                break;
            default:
                $builder->orderBy('w.started_at', 'ASC');
                break;
        }
        // $builder->addOrderBy('w.score', 'DESC')
        // $builder->addOrderBy('w.ended_at', 'DESC')
        return $builder->getQuery()->getResult();
    }


    public function findFirstThreeByQuizAndSession($quiz, $session): ?array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.quiz = :quiz')
            ->setParameter('quiz', $quiz)

            ->andWhere('w.session >= :session')
            ->setParameter('session', $session)

            ->groupBy('w.student')
            ->orderBy('w.score', 'DESC')
            ->addOrderBy('TIME_DIFF(w.ended_at, w.started_at, \'second\')', 'ASC')
            ->getQuery()
            ->setMaxResults(3)
            ->getResult();
    }


    //    /**
    //     * @return Workout[] Returns an array of Workout objects
    //     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Workout
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
