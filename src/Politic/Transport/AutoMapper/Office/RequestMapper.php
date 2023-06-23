<?php

declare(strict_types=1);

namespace App\Politic\Transport\AutoMapper\Office;

use App\General\Transport\AutoMapper\RestRequestMapper;

/**
 * Class RequestMapper
 *
 * @package App\Office
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'officeName',
        'officeDescription',
        'permission',
        'active',
        'users'
    ];
}
