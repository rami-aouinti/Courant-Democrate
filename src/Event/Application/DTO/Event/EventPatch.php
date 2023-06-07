<?php

declare(strict_types=1);

namespace App\Event\Application\DTO\Event;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class EventPatch
 *
 * @package App\Event
 */
class EventPatch extends Event
{
    use PatchUserGroups;
}
