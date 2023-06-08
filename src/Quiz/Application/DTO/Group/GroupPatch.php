<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Group;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class GroupPatch
 *
 * @package App\Group
 */
class GroupPatch extends Group
{
    use PatchUserGroups;
}
