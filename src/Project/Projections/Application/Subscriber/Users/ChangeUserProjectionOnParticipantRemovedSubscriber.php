<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasRemovedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeUserProjectionOnParticipantRemovedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectParticipantWasRemovedEvent::class];
    }

    public function __invoke(ProjectParticipantWasRemovedEvent $event): void
    {
        $projection = $this->userRepository->findByUserIdAndProjectId($event->participantId, $event->aggregateId);
        if (null === $projection) {
            throw new UserNotExistException($event->participantId);
        }

        $this->userRepository->delete($projection);
    }
}
