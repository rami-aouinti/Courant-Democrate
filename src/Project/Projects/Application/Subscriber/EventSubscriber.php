<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Subscriber;

use App\Project\Shared\Application\Bus\Event\EventSubscriberInterface;
use App\Project\Shared\Domain\Event\DomainEvent;
use App\Project\Projects\Application\Service\ProjectEventHandler;

final class EventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectEventHandler $eventHandler
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
