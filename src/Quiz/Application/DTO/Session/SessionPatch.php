<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Session;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class SessionPatch
 *
 * @package App\Session
 */
class SessionPatch extends Session
{
    use PatchUserGroups;
}
