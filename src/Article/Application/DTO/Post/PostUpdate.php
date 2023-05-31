<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;


/**
 * Class SettingUpdate
 *
 * @package App\Setting
 */
class PostUpdate extends Post
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
