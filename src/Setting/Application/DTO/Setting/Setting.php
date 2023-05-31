<?php

declare(strict_types=1);

namespace App\Setting\Application\DTO\Setting;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Setting\Domain\Entity\Setting as Entity;
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
class Setting extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $siteName = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $sidebarColor = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $sidebarTheme = '';

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName(string $siteName): self
    {
        $this->setVisited('siteName');
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSidebarColor(): string
    {
        return $this->sidebarColor;
    }

    /**
     * @param string $sidebarColor
     */
    public function setSidebarColor(string $sidebarColor): self
    {
        $this->setVisited('sidebarColor');
        $this->sidebarColor = $sidebarColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getSidebarTheme(): string
    {
        return $this->sidebarTheme;
    }

    /**
     * @param string $sidebarTheme
     */
    public function setSidebarTheme(string $sidebarTheme): self
    {
        $this->setVisited('sidebarTheme');
        $this->sidebarTheme = $sidebarTheme;

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
            $this->sidebarTheme = $entity->getSidebarTheme();
            $this->sidebarColor = $entity->getSidebarColor();
            $this->siteName = $entity->getSiteName();
        }

        return $this;
    }
}
