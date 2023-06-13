<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Score;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Score as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Score
 *
 * @package App\Quiz
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Score extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected int $score = 0;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected int $level = 0;



    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $text
     */
    public function setScore(int $score): self
    {
        $this->setVisited('score');
        $this->score = $score;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): self
    {
        $this->setVisited('level');
        $this->level = $level;

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
            $this->score = $entity->getScore();
            $this->level = $entity->getLevel();
        }

        return $this;
    }
}
