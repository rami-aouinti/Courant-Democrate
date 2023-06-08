<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Category;


use App\User\Application\DTO\Traits\PatchUserGroups;

/**
 * Class CategoryPatch
 *
 * @package App\Category
 */
class CategoryPatch extends Category
{
    use PatchUserGroups;
}
