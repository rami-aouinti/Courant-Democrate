<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Bus\Event;

interface EventSubscriberInterface
{
    public function subscribeTo(): array;
}
