<?php

namespace App\Setting\Domain\Entity;

use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Article\Domain\Entity\Traits\Blameable;
use App\Setting\Infrastructure\Repository\MenuRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: 'menu')]
class Menu implements EntityInterface
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
        'Menu',
        'Menu.id',
        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;


    #[ORM\Column(
        name: 'menuName',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Menu',
        'Menu.menuName',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $menuName = '';


    #[ORM\Column(
        name: 'menuPath',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Menu',
        'Menu.menuPath',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private bool $menuPath = true;


    #[ORM\Column(
        name: 'menuIcon',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Menu',
        'Menu.menuIcon',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $menuIcon = '';


    /**
     * One Menu has Many Menus.
     * @var Collection<int, Menu>
     */
    #[Groups([
        'Menu',
        'Menu.children',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[OneToMany(mappedBy: 'parent', targetEntity: Menu::class)]
    private Collection $children;

    /** Many Categories have One Category. */
    #[Groups([
        'Menu',
        'Menu.parent',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[ManyToOne(targetEntity: Menu::class, inversedBy: 'children')]
    #[JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private Menu|null $parent = null;

    /**
     * Constructor
     *
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->children = new ArrayCollection();
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
    public function getMenuName(): string
    {
        return $this->menuName;
    }

    /**
     * @param string $menuName
     */
    public function setMenuName(string $menuName): void
    {
        $this->menuName = $menuName;
    }

    /**
     * @return String
     */
    public function isMenuPath(): string
    {
        return $this->menuPath;
    }

    /**
     * @param string $menuPath
     */
    public function setMenuPath(string $menuPath): void
    {
        $this->menuPath = $menuPath;
    }



    /**
     * @return string
     */
    public function getMenuIcon(): string
    {
        return $this->menuIcon;
    }

    /**
     * @param string $menuIcon
     */
    public function setMenuIcon(string $menuIcon): void
    {
        $this->menuIcon = $menuIcon;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return Menu|null
     */
    public function getParent(): ?Menu
    {
        return $this->parent;
    }

    /**
     * @param Menu|null $parent
     */
    public function setParent(?Menu $parent): void
    {
        $this->parent = $parent;
    }

    public function __toString(): string
    {
        return $this->menuName;
    }
}
