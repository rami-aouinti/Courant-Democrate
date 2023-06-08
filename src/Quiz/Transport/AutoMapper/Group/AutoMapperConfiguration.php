<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Group;

use App\Quiz\Application\DTO\Group\GroupCreate;
use App\Quiz\Application\DTO\Group\GroupPatch;
use App\Quiz\Application\DTO\Group\GroupUpdate;
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
        GroupCreate::class,
        GroupPatch::class,
        GroupUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
