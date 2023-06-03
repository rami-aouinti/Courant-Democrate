<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Service;

use App\Project\Shared\Domain\Exception\LogicException;

// todo to application
final class DomainEventMapper
{
    private array $map = [];

    public function __construct(private readonly array $events)
    {
    }

    public function getMap(): array
    {
        $this->indexMap();

        return $this->map;
    }

    private function indexMap(): void
    {
        if (empty($this->map)) {
            foreach ($this->events as $eventClass) {
                $eventName = $eventClass::getEventName();
                if (isset($this->map[$eventName])) {
                    $message = sprintf(
                        'Event name "%s" of event "%s" already taken by event "%s".',
                        $eventName,
                        $eventClass,
                        $this->map[$eventName]
                    );
                    throw new LogicException($message);
                }
                $this->map[$eventName] = $eventClass;
            }
        }
    }
}
