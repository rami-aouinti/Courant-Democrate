<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Bus\Event;

use App\Project\Shared\Domain\Event\DomainEvent;

interface EventBusInterface
{
    public function dispatch(DomainEvent ...$events): void;
}
