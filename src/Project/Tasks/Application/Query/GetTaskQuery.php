<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryInterface;

final class GetTaskQuery implements QueryInterface
{
    public function __construct(public readonly string $id)
    {
    }
}
