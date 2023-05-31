<?php

declare(strict_types=1);

namespace App\Article\Transport\AutoMapper\Post;

use App\Article\Application\DTO\Post\PostCreate;
use App\Article\Application\DTO\Post\PostPatch;
use App\Article\Application\DTO\Post\PostUpdate;
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
        PostCreate::class,
        PostPatch::class,
        PostUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
