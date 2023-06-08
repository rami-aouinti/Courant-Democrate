<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Question;

use App\Quiz\Application\DTO\Question\QuestionCreate;
use App\Quiz\Application\DTO\Question\QuestionPatch;
use App\Quiz\Application\DTO\Question\QuestionUpdate;
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
        QuestionCreate::class,
        QuestionPatch::class,
        QuestionUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
