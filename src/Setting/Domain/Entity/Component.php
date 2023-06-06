<?php

namespace App\Setting\Domain\Entity;

use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Article\Domain\Entity\Traits\Blameable;
use App\Setting\Infrastructure\Repository\ComponentRepository;
use App\User\Domain\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
#[ORM\Table(name: 'component')]
class Component implements EntityInterface
{
    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_BASIC = 'set.UserBasic';


    use Blameable;
    use Timestampable;
    use Uuid;

    /**
     * @OA\Property(type="string", format="uuid")
     */
    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: UuidBinaryOrderedTimeType::NAME,
        unique: true,
        nullable: false,
    )]
    #[Groups([
        'Component',
        'Component.id',
        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;


    #[ORM\Column(
        name: 'componentName',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Component',
        'Component.componentName',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $componentName = '';


    #[ORM\Column(
        name: 'active',
        type: Types::BOOLEAN,
        nullable: false,
    )]
    #[Groups([
        'Component',
        'Component.active',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $active = true;

    /**
     * Constructor
     *
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getComponentName(): string
    {
        return $this->componentName;
    }

    /**
     * @param string $componentName
     */
    public function setComponentName(string $componentName): void
    {
        $this->componentName = $componentName;
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
        $this->active = $active;
    }
}
