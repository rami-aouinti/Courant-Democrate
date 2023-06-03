<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Tasks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskWasCreatedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Entity\TaskProjection;
use App\Project\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use DateTime;
use Exception;

final class CreateTaskProjectionOnTaskCreated implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $projectionRepository,
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskWasCreatedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(TaskWasCreatedEvent $event): void
    {
        $user = $this->userRepository->findByUserId($event->ownerId);
        if (null === $user) {
            throw new UserNotExistException($event->ownerId);
        }

        $projection = new TaskProjection(
            $event->taskId,
            $event->projectId,
            $event->name,
            $event->brief,
            $event->description,
            new DateTime($event->startDate),
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
