<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Session;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Session as Entity;
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
class Session extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected bool $sended_to_ed = false;

    /**
     * @return bool
     */
    public function getSendedToED(): bool
    {
        return $this->sended_to_ed;
    }

    /**
     * @param bool $sended_to_ed
     */
    public function setSendedToED(bool $sended_to_ed): self
    {
        $this->setVisited('sended_to_ed');
        $this->sended_to_ed = $sended_to_ed;

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
            $this->sended_to_ed = $entity->getSendedToED();
        }

        return $this;
    }
}
