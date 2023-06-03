<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Tasks\Domain\ValueObject\TaskLink;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy\DoctrineProxyCollectionItemInterface;

final class TaskLinkProxy implements DoctrineProxyCollectionItemInterface
{
    private TaskProxy $task;
    private string $toTaskId;
    private ?TaskLink $entity = null;

    public function __construct(TaskProxy $owner, TaskLink $entity)
    {
        $this->task = $owner;
        $this->entity = $entity;
    }

    public function getToTaskId(): string
    {
        return $this->toTaskId;
    }

    public function refresh(PersistentCollectionLoaderInterface $loader): void
    {
        $this->toTaskId = $this->entity->toTaskId->value;
    }

    public function changeEntity(TaskLink $entity): void
    {
        $this->entity = $entity;
    }

    public function getKey(): string
    {
        return $this->toTaskId;
    }
}
