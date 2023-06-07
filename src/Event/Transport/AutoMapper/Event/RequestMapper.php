<?php

declare(strict_types=1);

namespace App\Event\Transport\AutoMapper\Event;

use App\General\Transport\AutoMapper\RestRequestMapper;
use function array_map;

/**
 * Class RequestMapper
 *
 * @package App\User
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'title',
        'description',
        'allDays',
        'backgroundColor',
        'borderColor',
        'textColor',
        'className',
        'start',
        'end',
    ];

    public function __construct(
    ) {
    }

}
