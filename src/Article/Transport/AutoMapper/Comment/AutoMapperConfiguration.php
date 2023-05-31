<?php

declare(strict_types=1);

namespace App\Article\Transport\AutoMapper\Comment;

use App\Article\Application\DTO\Comment\CommentCreate;
use App\Article\Application\DTO\Comment\CommentPatch;
use App\Article\Application\DTO\Comment\CommentUpdate;
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
        CommentCreate::class,
        CommentPatch::class,
        CommentUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
