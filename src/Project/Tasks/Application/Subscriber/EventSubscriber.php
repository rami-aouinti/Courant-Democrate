<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Subscriber;

use App\Project\Tasks\Application\Service\TaskManagerEventHandler;
use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\DomainEvent;

final class EventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskManagerEventHandler $eventHandler
    ) {
    }

    public function subscribeTo(): array
    {
        return [];
    }

    public function __invoke(DomainEvent $event): void
    {
        $this->eventHandler->handle($event);
    }
}
