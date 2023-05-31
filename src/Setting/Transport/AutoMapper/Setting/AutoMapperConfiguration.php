<?php

declare(strict_types=1);

namespace App\Setting\Transport\AutoMapper\Setting;

use App\Setting\Application\DTO\Setting\SettingCreate;
use App\Setting\Application\DTO\Setting\SettingPatch;
use App\Setting\Application\DTO\Setting\SettingUpdate;
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
        SettingCreate::class,
        SettingPatch::class,
        SettingUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
