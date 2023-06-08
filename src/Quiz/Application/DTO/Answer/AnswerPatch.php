<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Answer;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class AnswerPatch
 *
 * @package App\Answer
 */
class AnswerPatch extends Answer
{
    use PatchUserGroups;
}
