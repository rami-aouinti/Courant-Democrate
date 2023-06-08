<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Question;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class QuestionPatch
 *
 * @package App\Question
 */
class QuestionPatch extends Question
{
    use PatchUserGroups;
}
