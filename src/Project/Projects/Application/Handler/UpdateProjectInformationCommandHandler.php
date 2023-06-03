<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Projects\Application\Command\UpdateProjectInformationCommand;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\ProjectDescription;
use App\Project\Projects\Domain\ValueObject\ProjectInformation;
use App\Project\Projects\Domain\ValueObject\ProjectName;

final class UpdateProjectInformationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(UpdateProjectInformationCommand $command): void
    {
        /** @var Project $project */
        $project = $this->projectRepository->findById(new ProjectId($command->id));
        if (null === $project) {
            throw new ProjectNotExistException($command->id);
        }

        $prevInfo = $project->getInformation();
        $project->changeInformation(
            new ProjectInformation(
                new ProjectName($command->name ?? $prevInfo->name->value),
                new ProjectDescription($command->description ?? $prevInfo->description->value),
                new DateTime($command->finishDate ?? $prevInfo->finishDate->getValue())
            ),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
