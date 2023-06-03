<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\CreateRequestToProjectCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\RequestId;

final class CreateRequestToProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticatorService,
    ) {
    }

    public function __invoke(CreateRequestToProjectCommand $command): void
    {
        $project = $this->repository->findById(new ProjectId($command->projectId));
        if (null === $project) {
            throw new ProjectNotExistException($command->projectId);
        }

        $project->createRequest(
            new RequestId($command->id),
            $this->authenticatorService->getAuthUser()->getId()
        );

        $this->repository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
