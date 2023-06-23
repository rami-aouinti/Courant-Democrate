<?php

declare(strict_types=1);

namespace App\Politic\Application\DTO\Office;

use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class OfficePatch
 *
 * @package App\Office
 */
class OfficePatch extends Office
{
    use PatchUserGroups;
}
