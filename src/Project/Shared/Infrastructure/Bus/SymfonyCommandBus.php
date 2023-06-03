<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Bus;

use App\Project\Shared\Application\Bus\Command\CommandBusInterface;
use App\Project\Shared\Application\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->handle($command);
    }
}
