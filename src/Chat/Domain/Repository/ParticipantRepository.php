<?php

namespace App\Chat\Domain\Repository;

use App\Chat\Domain\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField($conversationId, $userId): ?Participant
    {
        $qb =  $this->createQueryBuilder('p')
            ->andWhere('p.conversation = :val')
            ->andWhere('p.user = :user')
            ->setParameter('val', $conversationId)
            ->setParameter('user', $userId);
        return $qb
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findParticipantByConversationIdAndUserId(string $conversationId, string $userId): Participant|null
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('p.conversation', ':conversationId'),
                    $qb->expr()->eq('p.user', ':userId')
                )
            )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
        ;

        print_r($qb->getQuery()->getOneOrNullResult());
        return $qb->getQuery()->getOneOrNullResult();
    }
}
