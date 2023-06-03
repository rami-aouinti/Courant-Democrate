<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Projects;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectWasCreatedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Entity\ProjectProjection;
use App\Project\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use DateTime;
use Exception;

final class CreateProjectProjectionOnProjectCreated implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $projectionRepository,
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectWasCreatedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(ProjectWasCreatedEvent $event): void
    {
        $user = $this->userRepository->findByUserId($event->ownerId);
        if (null === $user) {
            throw new UserNotExistException($event->ownerId);
        }

        $projection = new ProjectProjection(
            $event->aggregateId,
            $event->ownerId,
            $event->name,
            $event->description,
            new DateTime($event->finishDate),
            (int) $event->status,
            $event->ownerId,
            $user->getFirstname(),
            $user->getLastname(),
            $user->getEmail()
        );

        $this->projectionRepository->save($projection);
    }
}
