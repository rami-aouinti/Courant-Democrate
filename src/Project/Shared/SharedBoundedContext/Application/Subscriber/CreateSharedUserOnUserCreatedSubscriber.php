<?php

declare(strict_types=1);

namespace App\Project\Shared\SharedBoundedContext\Application\Subscriber;

use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\Users\UserWasCreatedEvent;
use App\Project\Shared\SharedBoundedContext\Domain\Entity\SharedUser;
use App\Project\Shared\SharedBoundedContext\Domain\Repository\SharedUserRepositoryInterface;

final class CreateSharedUserOnUserCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SharedUserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserWasCreatedEvent::class];
    }

    public function __invoke(UserWasCreatedEvent $event): void
    {
        $user = new SharedUser($event->aggregateId);

        $this->userRepository->save($user);
        $this->eventBus->dispatch(...$user->releaseEvents());
    }
}
