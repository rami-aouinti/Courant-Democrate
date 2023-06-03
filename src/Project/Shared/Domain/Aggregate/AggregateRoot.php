<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\Aggregate;

use App\Project\Shared\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    public function registerEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
