<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectOwnerWasChangedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeUserProjectionOnProjectOwnerChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectOwnerWasChangedEvent::class];
    }

    public function __invoke(ProjectOwnerWasChangedEvent $event): void
    {
        $oldProjection = $this->userRepository->findByUserId($event->ownerId);
        if (null === $oldProjection) {
            throw new UserNotExistException($event->ownerId);
        }

        $projections = $this->userRepository->findAllByProjectId($event->aggregateId);
        foreach ($projections as $projection) {
            $projection->updateOwner($event->ownerId, $oldProjection);
            $this->userRepository->save($projection);
        }
    }
}
