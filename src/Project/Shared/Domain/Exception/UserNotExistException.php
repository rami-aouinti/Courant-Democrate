<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\Exception;

final class UserNotExistException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" doesn\'t exist',
            $userId
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
