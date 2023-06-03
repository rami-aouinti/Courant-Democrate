<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Tasks;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Tasks\TaskInformationWasChangedEvent;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use DateTime;
use Exception;

final class ChangeTaskProjectionOnTaskInformationChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $projectionRepository,
    ) {
    }

    public function subscribeTo(): array
    {
        return [TaskInformationWasChangedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(TaskInformationWasChangedEvent $event): void
    {
        $projection = $this->projectionRepository->findById($event->taskId);
        if (null === $projection) {
            throw new TaskNotExistException($event->taskId);
        }

        $projection->updateInformation(
            $event->name,
            $event->brief,
            $event->description,
            new DateTime($event->startDate),
            new DateTime($event->finishDate)
        );
        $this->projectionRepository->save($projection);
    }
}
