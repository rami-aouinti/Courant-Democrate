<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy\DoctrineProxyCollectionItemInterface;
use App\Project\Projects\Domain\Entity\Request;
use DateTime as PhpDateTime;

final class RequestProxy implements DoctrineProxyCollectionItemInterface
{
    private string $id;
    private string $userId;
    private int $status;
    private PhpDateTime $changeDate;
    private ProjectProxy $project;
    private ?Request $entity = null;

    public function __construct(ProjectProxy $parent, Request $entity)
    {
        $this->entity = $entity;
        $this->project = $parent;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getChangeDate(): PhpDateTime
    {
        return $this->changeDate;
    }

    public function refresh(PersistentCollectionLoaderInterface $loader): void
    {
        $this->id = $this->entity->getId()->value;
        $this->userId = $this->entity->getUserId()->value;
        $this->status = $this->entity->getStatus()->getScalar();
        $this->changeDate = $this->entity->getChangeDate()->getPhpDateTime();
    }

    public function changeEntity(Request $entity): void
    {
        $this->entity = $entity;
    }

    public function getKey(): string
    {
        return $this->id;
    }
}
