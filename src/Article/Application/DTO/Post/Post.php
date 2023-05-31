<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Post;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Article\Domain\Entity\Post as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class User
 *
 * @package App\User
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Post extends RestDto
{

    #[Assert\NotBlank]
    private ?string $title = null;

    private ?string $slug = null;

    #[Assert\NotBlank(message: 'post.blank_summary')]
    #[Assert\Length(max: 255)]
    private ?string $summary = null;

    #[Assert\NotBlank(message: 'post.blank_content')]
    #[Assert\Length(min: 10, minMessage: 'post.too_short_content')]
    private ?string $content = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): self
    {
        $this->setVisited('title');
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): self
    {
        $this->setVisited('slug');
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string|null $summary
     */
    public function setSummary(?string $summary): self
    {
        $this->setVisited('summary');
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): self
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
            $this->title = $entity->getTitle();
            $this->slug = $entity->getSlug();
            $this->summary = $entity->getSummary();
            $this->content = $entity->getContent();
        }

        return $this;
    }
}
