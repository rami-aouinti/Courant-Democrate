<?php

namespace App\Pdf\Domain\Entity;

use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Article\Domain\Entity\Traits\Blameable;
use App\Pdf\Infrastructure\Repository\PdfRepository;
use App\Politic\Domain\Entity\Office;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PdfRepository::class)]
#[ORM\Table(name: 'document_pdf')]
class Document implements EntityInterface
{
    final public const SET_USER_DOCUMENT = 'set.UserDocument';
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
        'Document',
        'Document.id',
        self::SET_USER_DOCUMENT,
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
        'Education',
        'Education.name',

        self::SET_USER_DOCUMENT,
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
        name: 'path',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Document',
        'Document.path',

        self::SET_USER_DOCUMENT,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $path = '';

    #[ORM\Column(
        name: 'date',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true,
    )]
    #[Groups([
        'Document',
        'Document.date',
    ])]
    protected ?DateTimeImmutable $date = null;



    #[ORM\Column(
        name: 'active',
        type: Types::BOOLEAN,
        nullable: false,
    )]
    #[Groups([
        'Document',
        'Document.active',

        self::SET_USER_DOCUMENT,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $active = true;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'educations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'Education',
        'Education.user',
        User::SET_USER_POST,
    ])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Office::class, mappedBy: 'documents')]
    private Collection $offices;

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
     * @return string
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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable|null $date
     */
    public function setDate(?DateTimeImmutable $date): void
    {
        $this->date = $date;
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

    /**
     * @return Collection
     */
    public function getOffices(): Collection
    {
        return $this->offices;
    }

    public function addOffice(Office $office): self
    {
        if (!$this->offices->contains($office)) {
            $this->offices[] = $office;
        }

        return $this;
    }

    public function removeOffice(Office $office): self
    {
        if ($this->offices->contains($office)) {
            $this->offices->removeElement($office);
        }

        return $this;
    }
}
