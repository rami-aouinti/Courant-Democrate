<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Command\UpdateTaskInformationCommand;
use App\Project\Tasks\Domain\Entity\Task;
use App\Project\Tasks\Domain\Exception\TaskManagerNotExistException;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Tasks\Domain\ValueObject\TaskBrief;
use App\Project\Tasks\Domain\ValueObject\TaskDescription;
use App\Project\Tasks\Domain\ValueObject\TaskInformation;
use App\Project\Tasks\Domain\ValueObject\TaskName;
use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;

final class UpdateTaskInformationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TaskManagerRepositoryInterface $managerRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(UpdateTaskInformationCommand $command): void
    {
        $taskId = new TaskId($command->id);
        $manager = $this->managerRepository->findByTaskId($taskId);
        if (null === $manager) {
            throw new TaskManagerNotExistException();
        }
        /** @var Task $task */
        $task = $manager->getTasks()->getCollection()->get($taskId->getHash());
        if (null === $task) {
            throw new TaskNotExistException($command->id);
        }

        $prevInfo = $task->getInformation();
        $manager->changeTaskInformation(
            $taskId,
            new TaskInformation(
                new TaskName($command->name ?? $prevInfo->name->value),
                new TaskBrief($command->brief ?? $prevInfo->brief->value),
                new TaskDescription($command->description ?? $prevInfo->description->value),
                new DateTime($command->startDate ?? $prevInfo->startDate->getValue()),
                new DateTime($command->finishDate ?? $prevInfo->finishDate->getValue())
            ),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->managerRepository->save($manager);
        $this->eventBus->dispatch(...$manager->releaseEvents());
    }
}
