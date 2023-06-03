<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Requests\RequestWasCreatedEvent;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnRequestCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [RequestWasCreatedEvent::class];
    }

    public function __invoke(RequestWasCreatedEvent $event): void
    {
        $projections = $this->projectionRepository->findAllById($event->aggregateId);

        foreach ($projections as $projection) {
            $projection->incrementPendingRequestsCount();
            $this->projectionRepository->save($projection);
        }
    }
}
