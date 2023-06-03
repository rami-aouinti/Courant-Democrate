<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class InsufficientPermissionsToChangeTaskException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Insufficient permissions to change this task', self::CODE_FORBIDDEN);
    }
}
