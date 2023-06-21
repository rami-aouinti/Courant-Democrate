<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class PostCreate
 *
 * @package App\Article
 */
class PostCreate extends Post
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected string $title = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected string $summary = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected string $content = '';

}
