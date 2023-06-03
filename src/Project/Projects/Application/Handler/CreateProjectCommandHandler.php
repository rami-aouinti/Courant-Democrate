<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\CreateProjectCommand;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\ProjectDescription;
use App\Project\Projects\Domain\ValueObject\ProjectInformation;
use App\Project\Projects\Domain\ValueObject\ProjectName;

final class CreateProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticatorService,
    ) {
    }

    public function __invoke(CreateProjectCommand $command): void
    {
        $project = Project::create(
            new ProjectId($command->id),
            new ProjectInformation(
                new ProjectName($command->name),
                new ProjectDescription($command->description),
                new DateTime($command->finishDate)
            ),
            new Owner(
                $this->authenticatorService->getAuthUser()->getId()
            ),
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
