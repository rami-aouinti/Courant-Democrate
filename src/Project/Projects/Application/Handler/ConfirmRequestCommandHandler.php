<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\ValueObject\Requests\ConfirmedRequestStatus;
use App\Project\Projects\Application\Command\ConfirmRequestCommand;
use App\Project\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Projects\Domain\ValueObject\RequestId;

final class ConfirmRequestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $repository,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(ConfirmRequestCommand $command): void
    {
        $requestId = new RequestId($command->id);
        $project = $this->repository->findByRequestId($requestId);
        if (null === $project) {
            throw new ProjectNotExistException();
        }

        $project->changeRequestStatus(
            $requestId,
            new ConfirmedRequestStatus(),
            $this->authenticator->getAuthUser()->getId()
        );

        $this->repository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}
