<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\Exception;

final class PageNotExistException extends DomainException
{
    public function __construct(int $page)
    {
        $message = sprintf(
            'Page "%s" doesn\'t exist',
            $page
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
