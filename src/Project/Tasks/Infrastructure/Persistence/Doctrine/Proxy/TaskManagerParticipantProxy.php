<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy\DoctrineProxyCollectionItemInterface;

final class TaskManagerParticipantProxy implements DoctrineProxyCollectionItemInterface
{
    private TaskManagerProxy $manager;
    private string $userId;
    private ?UserId $entity = null;

    public function __construct(TaskManagerProxy $owner, UserId $entity)
    {
        $this->manager = $owner;
        $this->entity = $entity;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function refresh(PersistentCollectionLoaderInterface $loader): void
    {
        $this->userId = $this->entity->value;
    }

    public function changeEntity(UserId $entity): void
    {
        $this->entity = $entity;
    }

    public function getKey(): string
    {
        return $this->userId;
    }
}
