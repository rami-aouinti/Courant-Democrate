<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Command\ActivateTaskCommand;
use App\Project\Tasks\Domain\Exception\TaskManagerNotExistException;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Tasks\Domain\ValueObject\ActiveTaskStatus;
use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;

final class ActivateTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TaskManagerRepositoryInterface $managerRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(ActivateTaskCommand $command): void
    {
        $taskId = new TaskId($command->id);
        $manager = $this->managerRepository->findByTaskId($taskId);
        if (null === $manager) {
            throw new TaskManagerNotExistException();
        }

        $manager->changeTaskStatus(
            $taskId,
            new ActiveTaskStatus(),
            $this->authenticator->getAuthUser()->getId(),
        );

        $this->managerRepository->save($manager);
        $this->eventBus->dispatch(...$manager->releaseEvents());
    }
}
