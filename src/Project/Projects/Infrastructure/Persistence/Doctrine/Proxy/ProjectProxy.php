<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy\DoctrineVersionedProxyInterface;
use App\Project\Projects\Domain\Entity\Project;
use DateTime as PhpDateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class ProjectProxy implements DoctrineVersionedProxyInterface
{
    private string $id;
    private string $name;
    private string $description;
    private PhpDateTime $finishDate;
    private int $status;
    private string $ownerId;
    private Collection $participants;
    private Collection $tasks;
    private Collection $requests;
    private int $version;
    private ?Project $entity = null;

    public function __construct(Project $entity)
    {
        $this->participants = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->entity = $entity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFinishDate(): PhpDateTime
    {
        return $this->finishDate;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function refresh(PersistentCollectionLoaderInterface $loader): void
    {
        $this->id = $this->entity->getId()->value;
        $this->name = $this->entity->getInformation()->name->value;
        $this->description = $this->entity->getInformation()->description->value;
        $this->finishDate = $this->entity->getInformation()->finishDate->getPhpDateTime();
        $this->status = $this->entity->getStatus()->getScalar();
        $this->ownerId = $this->entity->getOwner()->userId;
        $loader->loadInto(
            $this->participants,
            $this->entity->getParticipants()->getCollection(),
            $this
        );
        $loader->loadInto(
            $this->tasks,
            $this->entity->getTasks()->getCollection(),
            $this
        );
        $loader->loadInto(
            $this->requests,
            $this->entity->getRequests()->getCollection(),
            $this
        );
    }

    public function changeEntity(Project $entity): void
    {
        $this->entity = $entity;
    }
}
