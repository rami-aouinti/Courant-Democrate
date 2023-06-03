<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasAddedEvent;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnParticipantAddedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectParticipantWasAddedEvent::class];
    }

    public function __invoke(ProjectParticipantWasAddedEvent $event): void
    {
        $projections = $this->projectionRepository->findAllById($event->aggregateId);

        $first = true;
        foreach ($projections as $projection) {
            $projection->incrementParticipantsCount();
            $this->projectionRepository->save($projection);
            if ($first) {
                $this->projectionRepository->save($projection->createCopyForUser($event->participantId));
                $first = false;
            }
        }
    }
}
