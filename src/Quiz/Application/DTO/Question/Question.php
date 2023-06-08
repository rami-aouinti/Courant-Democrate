<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Question;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Question as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Quiz
 *
 * @package App\Quiz
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Question extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $text = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected int $max_duration;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): self
    {
        $this->setVisited('text');
        $this->text = $text;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxDuration(): int
    {
        return $this->max_duration;
    }

    /**
     * @param int $max_duration
     */
    public function setMaxDuration(int $max_duration): self
    {
        $this->setVisited('max_duration');
        $this->max_duration = $max_duration;

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
            $this->id = $entity->getId();
            $this->text = $entity->getText();
            $this->max_duration = $entity->getMaxDuration();
        }

        return $this;
    }
}
