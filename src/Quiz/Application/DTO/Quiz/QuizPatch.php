<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Quiz;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class QuizPatch
 *
 * @package App\Quiz
 */
class QuizPatch extends Quiz
{
    use PatchUserGroups;
}
