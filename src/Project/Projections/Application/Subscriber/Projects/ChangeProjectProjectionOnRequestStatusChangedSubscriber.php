<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Requests\RequestStatusWasChangedEvent;
use App\Project\Shared\Domain\ValueObject\Requests\RequestStatus;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnRequestStatusChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [RequestStatusWasChangedEvent::class];
    }

    public function __invoke(RequestStatusWasChangedEvent $event): void
    {
        $status = RequestStatus::createFromScalar((int) $event->status);
        if (!$status->isPending()) {
            $projections = $this->projectionRepository->findAllById($event->aggregateId);

            foreach ($projections as $projection) {
                $projection->decrementPendingRequestsCount();
                $this->projectionRepository->save($projection);
            }
        }
    }
}
