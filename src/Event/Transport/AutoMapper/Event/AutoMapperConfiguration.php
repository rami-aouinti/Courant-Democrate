<?php

declare(strict_types=1);

namespace App\Event\Transport\AutoMapper\Event;

use App\General\Transport\AutoMapper\RestAutoMapperConfiguration;
use App\Event\Application\DTO\Event\EventCreate;
use App\Event\Application\DTO\Event\EventPatch;
use App\Event\Application\DTO\Event\EventUpdate;

/**
 * Class AutoMapperConfiguration
 *
 * @package App\Event
 */
class AutoMapperConfiguration extends RestAutoMapperConfiguration
{
    /**
     * Classes to use specified request mapper.
     *
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [
        EventCreate::class,
        EventUpdate::class,
        EventPatch::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
