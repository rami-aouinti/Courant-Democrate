<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\Project\Shared\Domain\ValueObject\Users\UserId;

interface AuthUserInterface
{
    public function getId(): UserId;
}
