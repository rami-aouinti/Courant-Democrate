<?php

declare(strict_types=1);

namespace App\Setting\Application\DTO\Setting;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class SettingPatch
 *
 * @package App\Setting
 */
class SettingPatch extends Setting
{
    use PatchUserGroups;
}
