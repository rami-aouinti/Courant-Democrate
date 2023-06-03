<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Tasks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasDeletedEvent;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Projections\Domain\Repository\TaskProjectionRepositoryInterface;

final class ChangeTaskProjectionOnTaskLinkDeletedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskLinkWasDeletedEvent::class];
    }

    public function __invoke(TaskLinkWasDeletedEvent $event): void
    {
        $projection = $this->projectionRepository->findById($event->fromTaskId);
        if (null === $projection) {
            throw new TaskNotExistException($event->fromTaskId);
        }

        $projection->decrementLinksCount();
        $this->projectionRepository->save($projection);
    }
}
