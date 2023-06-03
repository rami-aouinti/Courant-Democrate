<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectOwnerWasChangedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeProjectProjectionOnProjectOwnerChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectOwnerWasChangedEvent::class];
    }

    public function __invoke(ProjectOwnerWasChangedEvent $event): void
    {
        $user = $this->userRepository->findByUserId($event->ownerId);
        if (null === $user) {
            throw new UserNotExistException($event->ownerId);
        }
        $projections = $this->projectionRepository->findAllById($event->aggregateId);

        foreach ($projections as $projection) {
            $projection->changeOwner(
                $event->ownerId,
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail()
            );
            $this->projectionRepository->save($projection);
        }
    }
}
