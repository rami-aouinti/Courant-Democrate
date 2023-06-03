<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class TaskUserNotExistException extends DomainException
{
    public function __construct(string $id)
    {
        $message = sprintf(
            'Task user "%s" doesn\'t exist',
            $id
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
