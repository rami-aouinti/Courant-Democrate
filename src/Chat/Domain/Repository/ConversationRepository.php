<?php

namespace App\Chat\Domain\Repository;

use App\Chat\Domain\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /**
     * @param $userId
     * @return Conversation[]|null
     */
    public function findOneBySomeField($userId): ?array
    {
        return $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'l.content',
                'u.username'
            )
            ->leftJoin('c.participants', 'r')
            ->where('r.id <> :id')
            ->leftJoin('c.last', 'l')
            ->leftJoin('r.user', 'u')
            ->groupBy('u.id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @param $conversationId
     * @return Conversation[]|null
     */
    public function findOneBySomeFieldByConversation($userId, $conversationId): ?array
    {
        return $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'l.content',
                'u.username'
            )
            ->where('r.id <>:id')
            ->leftJoin('c.participants', 'r')
            ->where('r.id <> :id')
            ->leftJoin('c.last', 'l')
            ->andwhere('l.content IS NOT NULL')
            ->leftJoin('r.user', 'u')
            ->setParameter('id', $userId)
            ->andwhere('c.id LIKE :conversationId')
            ->setParameter('conversationId', $conversationId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findConversationByParticipants(string $otherUserId, string $myId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select(
                $qb->expr()->count('p.conversation')
            )
            ->innerJoin('c.participants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.user', ':me'),
                    $qb->expr()->eq('p.user', ':otherUser')
                )
            )
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversation'),
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUserId
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationByUser(string $UserId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select(
                'otherUser.username',
                'c.id As conversationId',
                'lm.content',
                'lm.createdAt'
            )
            ->innerJoin(
                'c.participants', 'p', Join::WITH,
                $qb->expr()->neq('p.user', ':user')
            )
            ->innerJoin(
                'c.participants', 'me', Join::WITH,
                $qb->expr()->eq('me.user', ':user')
            )
            ->leftJoin('c.last', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->where('meUser.id LIKE :user')
            ->orderBy('lm.createdAt', 'DESC')
            ->setParameter('user', $UserId)
        ;
        return $qb->getQuery()->getResult();
    }

    public function checkIfUserisParticipant(int $conversationId, int $userId): ?Conversation
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->innerJoin('c.participants', 'p')
            ->where('c.id = :conversationId')
            ->andWhere(
                $qb->expr()->eq('p.user', ':userId')
            )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * @param $userId
     * @param $otherId
     * @return Conversation[]|null
     */
    public function findOneConversationBetweenUsers($userId, $otherId): ?array
    {

        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'l.content',
                'u.username'
            )
            ->leftJoin('c.participants', 'r')
            ->where('r.id = :id')
            ->leftJoin('c.last', 'l')
            ->leftJoin('r.user', 'u')
            ->groupBy('u.id')
            ->setParameter('id', $userId)
            ;
        print_r(count($qb->getQuery()->getResult()));
        return $qb->getQuery()->getResult();
    }
}
