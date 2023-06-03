<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Users\UserProfileWasChangedEvent;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeUserProjectionOnUserProfileChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserProfileWasChangedEvent::class];
    }

    public function __invoke(UserProfileWasChangedEvent $event): void
    {
        $projections = $this->userRepository->findAllByUserId($event->aggregateId);

        foreach ($projections as $projection) {
            $projection->updateProfile($event->firstname, $event->lastname);
            $this->userRepository->save($projection);
        }
    }
}
