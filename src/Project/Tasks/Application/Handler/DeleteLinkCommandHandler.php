<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Command\DeleteLinkCommand;
use App\Project\Tasks\Domain\Exception\TaskManagerNotExistException;
use App\Project\Tasks\Domain\Repository\TaskManagerRepositoryInterface;
use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;

final class DeleteLinkCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TaskManagerRepositoryInterface $managerRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(DeleteLinkCommand $command): void
    {
        $fromTaskId = new TaskId($command->fromTaskId);
        $manager = $this->managerRepository->findByTaskId($fromTaskId);
        if (null === $manager) {
            throw new TaskManagerNotExistException();
        }

        $manager->deleteTaskLink(
            $fromTaskId,
            new TaskId($command->toTaskId),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->managerRepository->save($manager);
        $this->eventBus->dispatch(...$manager->releaseEvents());
    }
}
