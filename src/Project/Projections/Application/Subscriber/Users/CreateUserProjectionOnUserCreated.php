<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Users;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Shared\Domain\Event\Users\UserWasCreatedEvent;
use App\Project\Projections\Domain\Entity\UserProjection;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use Exception;

final class CreateUserProjectionOnUserCreated implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserWasCreatedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(UserWasCreatedEvent $event): void
    {
        $projection = new UserProjection(
            $this->uuidGenerator->generate(),
            $event->firstname,
            $event->lastname,
            $event->email,
            $event->aggregateId
        );

        $this->userRepository->save($projection);
    }
}
