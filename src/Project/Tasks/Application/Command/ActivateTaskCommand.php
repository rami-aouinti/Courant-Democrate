<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Command;

use App\Project\Shared\Application\Bus\Command\CommandInterface;

final class ActivateTaskCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id
    ) {
    }
}
