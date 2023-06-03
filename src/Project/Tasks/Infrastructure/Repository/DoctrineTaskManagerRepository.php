<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Repository;

use App\Project\Tasks\Domain\Entity\TaskManager;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy\TaskManagerProxy;
use App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy\TaskManagerProxyFactory;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Infrastructure\Exception\OptimisticLockException;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Service\DoctrineOptimisticLockTrait;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

final class DoctrineTaskManagerRepository implements TaskManagerRepositoryInterface
{
    use DoctrineOptimisticLockTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PersistentCollectionLoaderInterface $collectionLoader,
        private readonly TaskManagerProxyFactory $managerProxyFactory,
    ) {
    }

    public function findByProjectId(ProjectId $id): ?TaskManager
    {
        /** @var TaskManagerProxy $proxy */
        $proxy = $this->getRepository()->findOneBy([
            'projectId' => $id->value,
        ]);

        return $this->managerProxyFactory->createEntity($proxy);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByTaskId(TaskId $id): ?TaskManager
    {
        /** @var TaskManagerProxy $proxy */
        $proxy = $this->getRepository()
            ->createQueryBuilder('t')
            ->leftJoin('t.tasks', 'r')
            ->where('r.id = :id')
            ->setParameter('id', $id->value)
            ->getQuery()
            ->getOneOrNullResult();

        return $this->managerProxyFactory->createEntity($proxy);
    }

    /**
     * @throws Exception
     * @throws OptimisticLockException
     */
    public function save(TaskManager $manager): void
    {
        $proxy = $this->getOrCreate($manager);

        $this->lock($this->entityManager, $proxy);

        $proxy->refresh($this->collectionLoader);

        $this->entityManager->persist($proxy);
        $this->entityManager->flush();
    }

    private function getOrCreate(TaskManager $manager): TaskManagerProxy
    {
        $result = $this->getRepository()->findOneBy([
            'id' => $manager->getId()->value,
        ]);
        if (null === $result) {
            $result = new TaskManagerProxy($manager);
        }

        return $result;
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(TaskManagerProxy::class);
    }
}
