<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Projects\ClosedProjectStatus;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\CloseProjectCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;

final class CloseProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(CloseProjectCommand $command): void
    {
        $project = $this->projectRepository->findById(new ProjectId($command->id));
        if (null === $project) {
            throw new ProjectNotExistException($command->id);
        }

        $project->changeStatus(
            new ClosedProjectStatus(),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
