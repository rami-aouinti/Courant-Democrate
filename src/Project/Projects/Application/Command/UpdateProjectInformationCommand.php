<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Command;

use App\Project\Shared\Application\Bus\Command\CommandInterface;

final class UpdateProjectInformationCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?string $finishDate
    ) {
    }
}
