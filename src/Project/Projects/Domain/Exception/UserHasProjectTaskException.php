<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class UserHasProjectTaskException extends DomainException
{
    public function __construct(string $id)
    {
        $message = sprintf(
            'User "%s" has task(s) in this project',
            $id
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
