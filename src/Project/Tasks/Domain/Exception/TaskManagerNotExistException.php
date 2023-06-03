<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class TaskManagerNotExistException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Task manager doesn\'t exist', self::CODE_NOT_FOUND);
    }
}
