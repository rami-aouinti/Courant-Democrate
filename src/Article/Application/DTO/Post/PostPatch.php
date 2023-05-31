<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class SettingPatch
 *
 * @package App\Setting
 */
class PostPatch extends Post
{
    use PatchUserGroups;
}
