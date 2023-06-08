<?php

declare(strict_types=1);

namespace App\Quiz\Transport\AutoMapper\Answer;

use App\Quiz\Application\DTO\Answer\AnswerCreate;
use App\Quiz\Application\DTO\Answer\AnswerPatch;
use App\Quiz\Application\DTO\Answer\AnswerUpdate;
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
        AnswerCreate::class,
        AnswerPatch::class,
        AnswerUpdate::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
