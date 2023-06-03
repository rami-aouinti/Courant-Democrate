<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
