<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

interface PasswordHasherInterface
{
    public function hashPassword(string $plainPassword): string;

    public function verifyPassword(string $hashedPassword, string $plainPassword): bool;
}
