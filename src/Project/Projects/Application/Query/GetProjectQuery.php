<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryInterface;

final class GetProjectQuery implements QueryInterface
{
    public function __construct(public readonly string $id)
    {
    }
}
