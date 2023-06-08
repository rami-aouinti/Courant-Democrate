<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Configuration;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class ConfigurationPatch
 *
 * @package App\Configuration
 */
class ConfigurationPatch extends Configuration
{
    use PatchUserGroups;
}
