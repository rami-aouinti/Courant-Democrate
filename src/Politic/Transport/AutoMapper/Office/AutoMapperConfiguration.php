<?php

declare(strict_types=1);

namespace App\Politic\Transport\AutoMapper\Office;

use App\General\Transport\AutoMapper\RestAutoMapperConfiguration;
use App\Politic\Application\DTO\Office\OfficeCreate;
use App\Politic\Application\DTO\Office\OfficePatch;
use App\Politic\Application\DTO\Office\OfficeUpdate;

/**
 * Class AutoMapperConfiguration
 *
 * @package App\Office
 */
class AutoMapperConfiguration extends RestAutoMapperConfiguration
{
    /**
     * Classes to use specified request mapper.
     *
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [
        OfficeCreate::class,
        OfficeUpdate::class,
        OfficePatch::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
