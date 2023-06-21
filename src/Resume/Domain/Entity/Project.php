<?php

namespace App\Resume\Domain\Entity;

use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Article\Domain\Entity\Traits\Blameable;
use App\Setting\Infrastructure\Repository\ComponentRepository;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
#[ORM\Table(name: 'resume_project')]
class Project implements EntityInterface
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
        'Project',
        'Project.id',
        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;


    #[ORM\Column(
        name: 'name',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.name',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $name = '';

    #[ORM\Column(
        name: 'role',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.role',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $role = '';

    #[ORM\Column(
        name: 'description',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.description',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $description = '';

    #[ORM\Column(
        name: 'missions',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.missions',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private array $missions = [];

    #[ORM\Column(
        name: 'url',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.url',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $url = '';


    #[ORM\Column(
        name: 'image',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.image',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $image = '';

    #[ORM\Column(
        name: 'startDate',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true,
    )]
    #[Groups([
        'Project',
        'Project.startDate',
    ])]
    protected ?DateTimeImmutable $startDate = null;

    #[ORM\Column(
        name: 'endDate',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true,
    )]
    #[Groups([
        'Project',
        'Project.endDate',
    ])]
    protected ?DateTimeImmutable $endDate = null;


    #[ORM\Column(
        name: 'active',
        type: Types::BOOLEAN,
        nullable: false,
    )]
    #[Groups([
        'Project',
        'Project.active',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $active = true;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'Project',
        'Project.user',
        User::SET_USER_POST,
    ])]
    private ?User $user = null;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getMissions(): array
    {
        return $this->missions;
    }

    /**
     * @param array $missions
     */
    public function setMissions(array $missions): void
    {
        $this->missions = $missions;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeImmutable|null $startDate
     */
    public function setStartDate(?DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param DateTimeImmutable|null $endDate
     */
    public function setEndDate(?DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
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
