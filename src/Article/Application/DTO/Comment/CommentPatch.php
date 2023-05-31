<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Comment;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class CommentPatch
 *
 * @package App\Article
 */
class CommentPatch extends Comment
{
    use PatchUserGroups;
}
