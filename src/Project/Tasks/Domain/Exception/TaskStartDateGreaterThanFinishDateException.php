<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class TaskStartDateGreaterThanFinishDateException extends DomainException
{
    public function __construct(string $startDate, string $finishDate)
    {
        $message = sprintf(
            'Task start date "%s" greater than finish date "%s"',
            $startDate,
            $finishDate
        );
        parent::__construct($message, self::CODE_UNPROCESSABLE_ENTITY);
    }
}
