<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Service;

use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Shared\Domain\Event\DomainEvent;
use App\Project\Shared\Domain\Event\Tasks\TaskWasCreatedEvent;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\ProjectTaskId;

final class ProjectEventHandler
{
    public function __construct(
        private readonly ProjectRepositoryInterface $repository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function handle(DomainEvent $event): void
    {
        $aggregateRoot = null;
        if ($event instanceof TaskWasCreatedEvent) {
            $aggregateRoot = $this->getProject($event->projectId);
            $this->createTask($aggregateRoot, $event);
        }

        if (null !== $aggregateRoot) {
            $this->repository->save($aggregateRoot);
            $this->eventBus->dispatch(...$aggregateRoot->releaseEvents());
        }
    }

    private function createTask(Project $aggregateRoot, TaskWasCreatedEvent $event): void
    {
        $aggregateRoot->createTask(
            new ProjectTaskId($this->uuidGenerator->generate()),
            new TaskId($event->taskId),
            new UserId($event->ownerId)
        );
    }

    private function getProject(string $projectId): Project
    {
        $aggregateRoot = $this->repository->findById(new ProjectId($projectId));
        if (null === $aggregateRoot) {
            throw new ProjectNotExistException($projectId);
        }

        return $aggregateRoot;
    }
}
