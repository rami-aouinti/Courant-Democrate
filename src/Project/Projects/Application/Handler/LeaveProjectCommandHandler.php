<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\LeaveProjectCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use Exception;

final class LeaveProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(LeaveProjectCommand $command): void
    {
        $project = $this->projectRepository->findById(new ProjectId($command->id));
        if (null === $project) {
            throw new ProjectNotExistException($command->id);
        }

        $currentUserId = $this->authenticator->getAuthUser()->getId();
        $project->removeParticipant(
            $currentUserId,
            $currentUserId
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
