<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Tasks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskStatusWasChangedEvent;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Projections\Domain\Repository\TaskProjectionRepositoryInterface;

final class ChangeTaskProjectionOnTaskStatusChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskStatusWasChangedEvent::class];
    }

    public function __invoke(TaskStatusWasChangedEvent $event): void
    {
        $projection = $this->projectionRepository->findById($event->taskId);
        if (null === $projection) {
            throw new TaskNotExistException($event->taskId);
        }

        $projection->changeStatus((int) $event->status);
        $this->projectionRepository->save($projection);
    }
}
