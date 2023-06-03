<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Shared\SharedBoundedContext\Domain\Repository\SharedUserRepositoryInterface;
use App\Project\Projects\Application\Command\ChangeProjectOwnerCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use Exception;

final class ChangeProjectOwnerCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly SharedUserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ChangeProjectOwnerCommand $command): void
    {
        $project = $this->projectRepository->findById(new ProjectId($command->id));
        if (null === $project) {
            throw new ProjectNotExistException($command->id);
        }
        $user = $this->userRepository->findById($command->ownerId);
        if (null === $user) {
            throw new UserNotExistException($command->ownerId);
        }

        $project->changeOwner(
            new Owner(
                new UserId($user->getId())
            ),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
