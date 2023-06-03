<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Service;

use App\Project\Tasks\Domain\Entity\TaskManager;
use App\Project\Tasks\Domain\Exception\TaskManagerNotExistException;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Tasks\Domain\ValueObject\TaskManagerId;
use App\Project\Tasks\Domain\ValueObject\Tasks;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Shared\Domain\Event\DomainEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectInformationWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectOwnerWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasAddedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasRemovedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectStatusWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectWasCreatedEvent;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Participants;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class TaskManagerEventHandler
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly TaskManagerRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function handle(DomainEvent $event): void
    {
        $aggregateRoot = null;

        if ($event instanceof ProjectWasCreatedEvent) {
            $aggregateRoot = $this->create($event);
        }
        if ($event instanceof ProjectStatusWasChangedEvent) {
            $aggregateRoot = $this->find($event);
            $this->changeStatus($aggregateRoot, $event);
        }
        if ($event instanceof ProjectOwnerWasChangedEvent) {
            $aggregateRoot = $this->find($event);
            $this->changeOwner($aggregateRoot, $event);
        }
        if ($event instanceof ProjectParticipantWasRemovedEvent) {
            $aggregateRoot = $this->find($event);
            $this->removeParticipant($aggregateRoot, $event);
        }
        if ($event instanceof ProjectParticipantWasAddedEvent) {
            $aggregateRoot = $this->find($event);
            $this->addParticipant($aggregateRoot, $event);
        }
        if ($event instanceof ProjectInformationWasChangedEvent) {
            $aggregateRoot = $this->find($event);
            $this->changeFinishDate($aggregateRoot, $event);
        }

        $this->save($aggregateRoot);
    }

    private function find(DomainEvent $event): TaskManager
    {
        $aggregateRoot = $this->repository->findByProjectId(new ProjectId($event->aggregateId));
        if (null === $aggregateRoot) {
            throw new TaskManagerNotExistException();
        }

        return $aggregateRoot;
    }

    private function save(?TaskManager $aggregateRoot): void
    {
        if (null !== $aggregateRoot) {
            $this->repository->save($aggregateRoot);
            $this->eventBus->dispatch(...$aggregateRoot->releaseEvents());
        }
    }

    private function create(ProjectWasCreatedEvent $event): TaskManager
    {
        return new TaskManager(
            new TaskManagerId($this->uuidGenerator->generate()),
            new ProjectId($event->aggregateId),
            ProjectStatus::createFromScalar((int) $event->status),
            new Owner(new UserId($event->ownerId)),
            new DateTime($event->finishDate),
            new Participants(),
            new Tasks()
        );
    }

    private function changeStatus(TaskManager $aggregateRoot, ProjectStatusWasChangedEvent $event): void
    {
        $status = ProjectStatus::createFromScalar((int) $event->status);
        $aggregateRoot->changeStatus($status);
    }

    private function changeOwner(TaskManager $aggregateRoot, ProjectOwnerWasChangedEvent $event): void
    {
        $aggregateRoot->changeOwner(new Owner(
            new UserId($event->ownerId)
        ));
    }

    private function removeParticipant(TaskManager $aggregateRoot, ProjectParticipantWasRemovedEvent $event): void
    {
        $aggregateRoot->removeParticipant(new UserId($event->participantId));
    }

    private function addParticipant(TaskManager $aggregateRoot, ProjectParticipantWasAddedEvent $event): void
    {
        $aggregateRoot->addParticipant(new UserId($event->participantId));
    }

    private function changeFinishDate(TaskManager $aggregateRoot, ProjectInformationWasChangedEvent $event): void
    {
        $aggregateRoot->changeFinishDate(new DateTime($event->finishDate));
    }
}
