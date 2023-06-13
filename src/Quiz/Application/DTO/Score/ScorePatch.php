<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Score;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class ScorePatch
 *
 * @package App\Score
 */
class ScorePatch extends Score
{
    use PatchUserGroups;
}
