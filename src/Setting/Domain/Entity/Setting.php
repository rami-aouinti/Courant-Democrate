<?php

namespace App\Setting\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Setting\Domain\Entity\Traits\Blameable;
use App\Setting\Domain\Repository\SettingRepository;
use App\User\Domain\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[ORM\Table(name: 'setting')]
class Setting
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
        'Setting',
        'Setting.id',
        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;


    #[ORM\Column(
        name: 'siteName',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Setting',
        'Setting.siteName',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $siteName = '';


    #[ORM\Column(
        name: 'drawer',
        type: Types::BOOLEAN,
        nullable: false,
    )]
    #[Groups([
        'Setting',
        'Setting.drawer',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $drawer = true;


    #[ORM\Column(
        name: 'sidebarColor',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Setting',
        'Setting.sidebarColor',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $sidebarColor = '';


    #[ORM\Column(
        name: 'sidebarTheme',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Setting',
        'Setting.sidebarTheme',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $sidebarTheme = '';



    /** One Cart has One Customer. */
    #[OneToOne(inversedBy: 'setting', targetEntity: User::class)]
    private User|null $user = null;


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
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * @return bool
     */
    public function isDrawer(): bool
    {
        return $this->drawer;
    }

    /**
     * @param bool $drawer
     */
    public function setDrawer(bool $drawer): void
    {
        $this->drawer = $drawer;
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
    public function setSidebarColor(string $sidebarColor): void
    {
        $this->sidebarColor = $sidebarColor;
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
    public function setSidebarTheme(string $sidebarTheme): void
    {
        $this->sidebarTheme = $sidebarTheme;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
