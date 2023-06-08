<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\School;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class SchoolPatch
 *
 * @package App\School
 */
class SchoolPatch extends School
{
    use PatchUserGroups;
}
