<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;


/**
 * Class PostUpdate
 *
 * @package App\Article
 */
class PostUpdate extends Post
{

    protected string $title = '';
    protected string $slug = '';

    protected string $summary = '';

    protected string $content = '';

}
