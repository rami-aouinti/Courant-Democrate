<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\School;

use App\Quiz\Application\DTO\School\SchoolCreate;
use App\Quiz\Application\DTO\School\SchoolPatch;
use App\Quiz\Application\DTO\School\SchoolUpdate;
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
        SchoolCreate::class,
        SchoolPatch::class,
        SchoolUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
