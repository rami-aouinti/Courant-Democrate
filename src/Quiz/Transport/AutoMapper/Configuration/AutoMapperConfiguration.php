<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Configuration;

use App\Quiz\Application\DTO\Configuration\ConfigurationCreate;
use App\Quiz\Application\DTO\Configuration\ConfigurationPatch;
use App\Quiz\Application\DTO\Configuration\ConfigurationUpdate;
use App\General\Transport\AutoMapper\RestAutoMapperConfiguration;

/**
 * Class AutoMapperConfiguration
 *
 * @package App\ApiKey
 */
class AutoMapperConfiguration extends RestAutoMapperConfiguration
{
    /**
     * Classes to use specified request mapper.
     *
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [
        ConfigurationCreate::class,
        ConfigurationPatch::class,
        ConfigurationUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
