<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Session;

use App\Quiz\Application\DTO\Session\SessionCreate;
use App\Quiz\Application\DTO\Session\SessionPatch;
use App\Quiz\Application\DTO\Session\SessionUpdate;
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
        SessionCreate::class,
        SessionPatch::class,
        SessionUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
