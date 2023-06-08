<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Answer;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Answer as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Answer
 *
 * @package App\Quiz
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Answer extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $text = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected bool $correct = false;



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
     * @return bool
     */
    public function getCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct): self
    {
        $this->setVisited('correct');
        $this->correct = $correct;

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
            $this->correct = $entity->getCorrect();
        }

        return $this;
    }
}
