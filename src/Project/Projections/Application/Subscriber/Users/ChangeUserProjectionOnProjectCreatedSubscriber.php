<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectWasCreatedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeUserProjectionOnProjectCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectWasCreatedEvent::class];
    }

    public function __invoke(ProjectWasCreatedEvent $event): void
    {
        $oldProjection = $this->userRepository->findByUserId($event->ownerId);
        if (null === $oldProjection) {
            throw new UserNotExistException($event->ownerId);
        }
        $projection = $oldProjection->createCopyForProject(
            $this->uuidGenerator->generate(),
            $event->aggregateId
        );
        $projection->updateOwner($event->ownerId, $oldProjection);
        $this->userRepository->save($projection);
    }
}
