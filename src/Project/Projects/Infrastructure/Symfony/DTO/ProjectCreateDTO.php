<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Symfony\DTO;

use App\Project\Projects\Application\Command\CreateProjectCommand;

final readonly class ProjectCreateDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public string $finishDate,
    ) {
    }

    public function createCommand(string $id): CreateProjectCommand
    {
        return new CreateProjectCommand(
            $id,
            $this->name,
            $this->description,
            $this->finishDate,
        );
    }
}
