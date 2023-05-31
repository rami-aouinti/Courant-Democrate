<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class PostPatch
 *
 * @package App\Article
 */
class PostPatch extends Post
{
    use PatchUserGroups;
}
