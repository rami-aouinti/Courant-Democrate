<?php

declare(strict_types=1);

namespace App\Project\Projections\Application\Subscriber\Requests;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Requests\RequestWasCreatedEvent;
use App\Project\Shared\Domain\Exception\UserNotExistException;
use App\Project\Projections\Domain\Entity\RequestProjection;
use App\Project\Projections\Domain\Repository\RequestProjectionRepositoryInterface;
use App\Project\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use DateTime;
use Exception;

final class CreateOnRequestCreated implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestProjectionRepositoryInterface $requestRepository,
        private readonly UserProjectionRepositoryInterface $userRepository
    ) {
    }

    public function subscribeTo(): array
    {
        return [RequestWasCreatedEvent::class];
    }

    /**
     * @throws Exception
     */
    public function __invoke(RequestWasCreatedEvent $event): void
    {
        $user = $this->userRepository->findByUserId($event->userId);
        if (null === $user) {
            throw new UserNotExistException($event->userId);
        }

        $projection = new RequestProjection(
            $event->requestId,
            $event->aggregateId,
            (int) $event->status,
            new DateTime($event->changeDate),
            $event->userId,
            $user->getFirstname(),
            $user->getLastname(),
            $user->getEmail()
        );

        $this->requestRepository->save($projection);
    }
}
