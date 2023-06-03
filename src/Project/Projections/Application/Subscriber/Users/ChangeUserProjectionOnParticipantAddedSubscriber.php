<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasAddedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;

final class ChangeUserProjectionOnParticipantAddedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function subscribeTo(): array
    {
        return [ProjectParticipantWasAddedEvent::class];
    }

    public function __invoke(ProjectParticipantWasAddedEvent $event): void
    {
        $projection = $this->userRepository->findByUserId($event->participantId);
        if (null === $projection) {
            throw new UserNotExistException($event->participantId);
        }
        $projection = $projection->createCopyForProject(
            $this->uuidGenerator->generate(),
            $event->aggregateId
        );
        $this->userRepository->save($projection);
    }
}
