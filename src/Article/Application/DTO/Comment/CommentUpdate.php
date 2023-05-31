<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Comment;


/**
 * Class SettingUpdate
 *
 * @package App\Setting
 */
class CommentUpdate extends Comment
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
