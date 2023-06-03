<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Projects\ActiveProjectStatus;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\ActivateProjectCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;

final readonly class ActivateProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface    $projectRepository,
        private EventBusInterface             $eventBus,
        private AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(ActivateProjectCommand $command): void
    {
        $project = $this->projectRepository->findById(new ProjectId($command->id));
        if (null === $project) {
            throw new ProjectNotExistException($command->id);
        }

        $project->changeStatus(
            new ActiveProjectStatus(),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
