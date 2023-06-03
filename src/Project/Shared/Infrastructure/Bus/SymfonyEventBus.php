<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Bus;

use App\Project\Shared\Application\Bus\Event\EventBusInterface;
use App\Project\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyEventBus implements EventBusInterface
{
    public function __construct(private readonly MessageBusInterface $eventBus)
    {
    }

    public function dispatch(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
