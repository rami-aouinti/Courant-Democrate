<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Users\UserProfileWasChangedEvent;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;

final class ChangeProjectProjectionOnUserProfileChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserProfileWasChangedEvent::class];
    }

    public function __invoke(UserProfileWasChangedEvent $event): void
    {
        $projections = $this->projectionRepository->findAllByOwnerId($event->aggregateId);

        foreach ($projections as $projection) {
            $projection->changeOwnerProfile($event->firstname, $event->lastname);
            $this->projectionRepository->save($projection);
        }
    }
}
