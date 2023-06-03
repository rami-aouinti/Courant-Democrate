<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\User\Domain\Entity\User;

interface AuthenticatorServiceInterface
{
    public function getAuthUser(): ?User;

    public function getToken(string $id): string;
}
