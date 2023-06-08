<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Quiz;

use App\Quiz\Application\DTO\Quiz\QuizCreate;
use App\Quiz\Application\DTO\Quiz\QuizPatch;
use App\Quiz\Application\DTO\Quiz\QuizUpdate;
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
        QuizCreate::class,
        QuizPatch::class,
        QuizUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
