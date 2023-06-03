<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Bus;

use App\Project\Shared\Application\Bus\Query\QueryBusInterface;
use App\Project\Shared\Application\Bus\Query\QueryInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(QueryInterface $query): QueryResponseInterface
    {
        return $this->handle($query);
    }
}
