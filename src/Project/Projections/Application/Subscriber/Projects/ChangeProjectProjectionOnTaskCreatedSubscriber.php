<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskWasCreatedEvent;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnTaskCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskWasCreatedEvent::class];
    }

    public function __invoke(TaskWasCreatedEvent $event): void
    {
        $projections = $this->projectionRepository->findAllById($event->projectId);

        foreach ($projections as $projection) {
            $projection->incrementTasksCount();
            $this->projectionRepository->save($projection);
        }
    }
}
