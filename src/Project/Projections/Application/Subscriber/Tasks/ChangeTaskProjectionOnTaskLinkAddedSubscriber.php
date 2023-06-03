<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Tasks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasAddedEvent;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Projections\Domain\Repository\TaskProjectionRepositoryInterface;

final class ChangeTaskProjectionOnTaskLinkAddedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskLinkWasAddedEvent::class];
    }

    public function __invoke(TaskLinkWasAddedEvent $event): void
    {
        $projection = $this->projectionRepository->findById($event->fromTaskId);
        if (null === $projection) {
            throw new TaskNotExistException($event->fromTaskId);
        }

        $projection->incrementLinksCount();
        $this->projectionRepository->save($projection);
    }
}
