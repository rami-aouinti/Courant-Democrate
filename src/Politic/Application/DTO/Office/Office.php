<?php

declare(strict_types=1);

namespace App\Politic\Application\DTO\Office;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Application\Validator\Constraints as GeneralAppAssert;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Politic\Domain\Entity\Office as Entity;
use App\User\Domain\Entity\User as UserEntity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Office
 *
 * @package App\Office
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Office extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $officeName = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $officeDescription = '';

    protected array $permission = [];


    protected bool $active = false;

    /**
     * @var Entity[]|array<int, Entity>
     */
    #[GeneralAppAssert\EntityReferenceExists(entityClass: UserEntity::class)]
    protected array $users = [];


    public function getOfficeName(): string
    {
        return $this->officeName;
    }

    public function setOfficeName(string $officeName): self
    {
        $this->setVisited('officeName');
        $this->officeName = $officeName;

        return $this;
    }

    public function getOfficeDescription(): string
    {
        return $this->officeDescription;
    }

    public function setOfficeDescription(string $officeDescription): self
    {
        $this->setVisited('officeDescription');
        $this->officeDescription = $officeDescription;

        return $this;
    }

    /**
     * @return array
     */
    public function getPermission(): array
    {
        return $this->permission;
    }

    /**
     * @param array $permission
     */
    public function setPermission(array $permission): void
    {
        $this->setVisited('permission');
        $this->permission = $permission;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->setVisited('active');
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array $users): void
    {
        $this->setVisited('users');
        $this->users = $users;
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
            $this->officeName = $entity->getOfficeName();
            $this->officeDescription = $entity->getOfficeDescription();
            $this->permission = $entity->getPermission();
            $this->active = $entity->isActive();
            /** @var array<int, UserEntity> $groups */
            $users = $entity->getUsers();
            $this->users = $users;
        }

        return $this;
    }

}
