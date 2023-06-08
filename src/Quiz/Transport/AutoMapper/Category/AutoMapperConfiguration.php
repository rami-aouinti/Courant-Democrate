<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Category;

use App\Quiz\Application\DTO\Category\CategoryCreate;
use App\Quiz\Application\DTO\Category\CategoryPatch;
use App\Quiz\Application\DTO\Category\CategoryUpdate;
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
        CategoryCreate::class,
        CategoryPatch::class,
        CategoryUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
