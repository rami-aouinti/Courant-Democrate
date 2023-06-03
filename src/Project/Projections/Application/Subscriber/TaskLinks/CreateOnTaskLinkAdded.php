<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\TaskLinks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasAddedEvent;
use App\Project\Projections\Domain\Entity\TaskLinkProjection;
use App\Project\Projections\Domain\Repository\TaskLinkProjectionRepositoryInterface;
use Exception;

final class CreateOnTaskLinkAdded implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskLinkProjectionRepositoryInterface $repository
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskLinkWasAddedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(TaskLinkWasAddedEvent $event): void
    {
        $projection = new TaskLinkProjection(
            $event->fromTaskId,
            $event->toTaskId
        );

        $this->repository->save($projection);
    }
}
