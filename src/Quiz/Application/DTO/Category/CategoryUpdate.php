<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Category;


/**
 * Class CategoryUpdate
 *
 * @package App\Category
 */
class CategoryUpdate extends Category
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
