<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Exception;

use App\Project\Shared\Domain\Exception\DomainException;

final class TaskFinishDateGreaterThanProjectFinishDateException extends DomainException
{
    public function __construct(string $projectFinishDate, string $finishDate)
    {
        $message = sprintf(
            'Task finish date "%s" greater than project finish date "%s"',
            $finishDate,
            $projectFinishDate
        );
        parent::__construct($message, self::CODE_UNPROCESSABLE_ENTITY);
    }
}
