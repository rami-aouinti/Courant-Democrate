<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Repository;

use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Infrastructure\Exception\OptimisticLockException;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Service\DoctrineOptimisticLockTrait;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\RequestId;
use App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy\ProjectProxy;
use App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy\ProjectProxyFactory;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

final class DoctrineProjectRepository implements ProjectRepositoryInterface
{
    use DoctrineOptimisticLockTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PersistentCollectionLoaderInterface $collectionLoader,
        private readonly ProjectProxyFactory $projectProxyFactory
    ) {
    }

    /**
     * @throws Exception
     */
    public function findById(ProjectId $id): ?Project
    {
        /** @var ProjectProxy $proxy */
        $proxy = $this->getRepository()->findOneBy([
            'id' => $id->value,
        ]);

        return $this->projectProxyFactory->createEntity($proxy);
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function findByRequestId(RequestId $id): ?Project
    {
        /** @var ProjectProxy $proxy */
        $proxy = $this->getRepository()
            ->createQueryBuilder('t')
            ->leftJoin('t.requests', 'r')
            ->where('r.id = :id')
            ->setParameter('id', $id->value)
            ->getQuery()
            ->getOneOrNullResult();

        return $this->projectProxyFactory->createEntity($proxy);
    }

    /**
     * @throws Exception
     * @throws OptimisticLockException
     */
    public function save(Project $project): void
    {
        $proxy = $this->getOrCreate($project);

        $this->lock($this->entityManager, $proxy);

        $proxy->refresh($this->collectionLoader);

        $this->entityManager->persist($proxy);
        $this->entityManager->flush();
    }

    private function getOrCreate(Project $project): ProjectProxy
    {
        $result = $this->getRepository()->findOneBy([
            'id' => $project->getId()->value,
        ]);
        if (null === $result) {
            $result = new ProjectProxy($project);
        }

        return $result;
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(ProjectProxy::class);
    }
}
