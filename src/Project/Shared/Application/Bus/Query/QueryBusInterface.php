<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Bus\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): QueryResponseInterface;
}
