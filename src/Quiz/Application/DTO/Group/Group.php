<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Group;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Group as Entity;
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
class Group extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $name = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $code = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $shortname = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected int $ed_id;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->setVisited('name');
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): self
    {
        $this->setVisited('code');
        $this->code = $code;
        return $this;
    }

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
    public function setShortname(string $shortname): self
    {
        $this->setVisited('shortname');
        $this->shortname = $shortname;
        return $this;
    }

    /**
     * @return int
     */
    public function getEdId(): int
    {
        return $this->ed_id;
    }

    /**
     * @param int $ed_id
     */
    public function setEdId(int $ed_id): self
    {
        $this->setVisited('ed_id');
        $this->ed_id = $ed_id;
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
            $this->name = $entity->getName();
            $this->code = $entity->getCode();
            $this->shortname = $entity->getShortname();
            $this->ed_id = $entity->getEdId();
        }

        return $this;
    }
}
