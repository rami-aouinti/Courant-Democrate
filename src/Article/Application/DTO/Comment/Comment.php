<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Comment;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Article\Domain\Entity\Comment as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Comment
 *
 * @package App\Article
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Comment extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected string $content = '';

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): self
    {
        $this->setVisited('content');
        $this->content = $content;

        return $this;
    }


    /**
     * {@inheritdoc}
     *
     * @param EntityInterface|Entity $entity
     */
    public function load(EntityInterface $entity): self
    {
        if ($entity instanceof Entity) {
            $this->content = $entity->getContent();
        }

        return $this;
    }
}
