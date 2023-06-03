<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectStatusWasChangedEvent;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnProjectStatusChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectStatusWasChangedEvent::class];
    }

    public function __invoke(ProjectStatusWasChangedEvent $event): void
    {
        $projections = $this->projectionRepository->findAllById($event->aggregateId);

        foreach ($projections as $projection) {
            $projection->changeStatus((int) $event->status);
            $this->projectionRepository->save($projection);
        }
    }
}
