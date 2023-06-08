<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Category;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Category as Entity;
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
class Category extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $shortname = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $longname = '';

    /**
     * @return string
     */
    public function getShortname(): string
    {
        return $this->shortname;
    }

    /**
     * @param string $shortname
     */
    public function setLongname(string $shortname): self
    {
        $this->setVisited('shortname');
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLongname(): string
    {
        return $this->longname;
    }

    /**
     * @param string $longname
     */
    public function setSidebarColor(string $longname): self
    {
        $this->setVisited('longname');
        $this->longname = $longname;

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
            $this->shortname = $entity->getShortname();
            $this->longname = $entity->getLongname();
        }

        return $this;
    }
}
