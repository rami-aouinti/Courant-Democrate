<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Requests;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Users\UserProfileWasChangedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Repository\RequestProjectionRepositoryInterface;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use Exception;

final class ChangeOnUserProfileChanged implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestProjectionRepositoryInterface $requestRepository,
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserProfileWasChangedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(UserProfileWasChangedEvent $event): void
    {
        $user = $this->userRepository->findByUserId($event->aggregateId);
        if (null === $user) {
            throw new UserNotExistException($event->aggregateId);
        }

        $projections = $this->requestRepository->findByUserId($event->aggregateId);
        foreach ($projections as $projection) {
            $projection->changeUserProfile($event->firstname, $event->lastname);
            $this->requestRepository->save($projection);
        }
    }
}
