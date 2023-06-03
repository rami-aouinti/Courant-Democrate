<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Command\CreateTaskCommand;
use App\Project\Tasks\Domain\Exception\TaskManagerNotExistException;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Tasks\Domain\ValueObject\TaskBrief;
use App\Project\Tasks\Domain\ValueObject\TaskDescription;
use App\Project\Tasks\Domain\ValueObject\TaskInformation;
use App\Project\Tasks\Domain\ValueObject\TaskName;
use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Shared\SharedBoundedContext\Domain\Repository\SharedUserRepositoryInterface;

final class CreateTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TaskManagerRepositoryInterface $managerRepository,
        private readonly SharedUserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(CreateTaskCommand $command): void
    {
        $manager = $this->managerRepository->findByProjectId(new ProjectId($command->projectId));
        if (null === $manager) {
            throw new TaskManagerNotExistException();
        }
        $userId = null !== $command->ownerId
            ? new UserId($command->ownerId)
            : $this->authenticator->getAuthUser()->getId();
        $user = $this->userRepository->findById($userId->value);
        if (null === $user) {
            throw new UserNotExistException($userId->value);
        }

        $manager->createTask(
            new TaskId($command->id),
            new TaskInformation(
                new TaskName($command->name),
                new TaskBrief($command->brief),
                new TaskDescription($command->description),
                new DateTime($command->startDate),
                new DateTime($command->finishDate)
            ),
            $userId,
            $this->authenticator->getAuthUser()->getId()
        );

        $this->managerRepository->save($manager);
        $this->eventBus->dispatch(...$manager->releaseEvents());
    }
}
