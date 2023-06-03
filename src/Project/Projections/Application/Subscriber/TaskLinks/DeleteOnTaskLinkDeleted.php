<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\TaskLinks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasDeletedEvent;
use App\Project\Shared\Domain\Exception\TaskLinkNotExistException;
use App\Project\Projections\Domain\Repository\TaskLinkProjectionRepositoryInterface;
use Exception;

final class DeleteOnTaskLinkDeleted implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskLinkProjectionRepositoryInterface $repository
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskLinkWasDeletedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(TaskLinkWasDeletedEvent $event): void
    {
        $projection = $this->repository->findById($event->fromTaskId, $event->toTaskId);
        if (null === $projection) {
            throw new TaskLinkNotExistException($event->fromTaskId, $event->toTaskId);
        }

        $this->repository->delete($projection);
    }
}
